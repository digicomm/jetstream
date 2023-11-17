<?php

namespace App\Models;

use DateTime;
use Exception;
use Illuminate\Database\Eloquent\Model;
use stdClass;

class SP_PostedShipments extends Model
{

    protected $connection = 'digismart';
    protected $table      = 'SP_PostedShipments';
    protected $casts      = [
        'posted' => 'double'
    ];

    public static function getShippedYet($salesOrder, $poLine, $productCode)
    {
        return self::where('sales_order_no', $salesOrder)
            ->where('pol', $poLine)
            ->where('product_code', $productCode)
            ->where('date_posted', date('Y-m-d'))
            ->first();
    }

    public static function getEndOfDayMissingPickSheetCount()
    {
        $saturday = self::getSaturday();

        return self::select('order_number')
            ->where('date_posted', '>=', $saturday)
            ->where('date_posted', '<=', date('Y-m-d'))
            ->whereNotIn('order_number', function ($query) {
                $query->select('pick_sheet')
                    ->from('ED_PickSheets')
                    ->where('date', '=', date('Y-m-d'))
                    ->get();
            })
            ->groupBy('order_number')
            ->get()
            ->count();
    }


    public static function getEndOfDayMissingPickSheets()
    {
        $saturday = self::getSaturday();

        return self::select('order_number', 'customer_name', 'sage_sales_order', 'ship_to_city', 'scac', 'bol_pro', 'bill_to_code')
            ->selectRaw('LEFT(ship_to_code,6) as ship_to_code, timestampdiff(SECOND, time_posted, CURRENT_TIMESTAMP ) as posted')
            ->where('date_posted', '>=', $saturday)
            ->where('date_posted', '<=', date('Y-m-d'))
            ->whereNotIn('order_number', function ($query) {
                $query->select('pick_sheet')
                    ->from('ED_PickSheets')
                    ->where('date', '=', date('Y-m-d'))
                    ->get();
            })
            ->orderBy('order_number', 'ASC')
            ->groupBy('order_number', 'customer_name', 'sage_sales_order', 'ship_to_city', 'scac', 'bol_pro', 'bill_to_code', 'ship_to_code', 'posted')
            ->get();
    }

    public static function getPickSheetCheck($pick_sheet)
    {
        $saturday = self::getSaturday();

        return self::select('order_number')
            ->where(function ($query) use ($saturday) {
                $query->where('date_posted', '=', date('Y-m-d'))
                    ->orWhere('date_posted', '=', $saturday);
            })
            ->where('order_number', $pick_sheet)
            ->count();
    }

    public static function checkPickSheet($pickSheet)
    {
        return self::select('order_number')
            ->where('date_posted', '=', date('Y-m-d'))
            ->where('order_number', $pickSheet)
            ->count();
    }

    public static function getShippedOrders()
    {
        $saturday = self::getSaturday();

        return self::select('shipment_date', 'ship_to_code', 'ship_to_city', 'customer_name', 'order_number', 'sage_sales_order', 'purchase_order_number', 'scac', 'bol_pro')
            ->selectRaw('ROUND(timestampdiff(SECOND, time_posted, CURRENT_TIMESTAMP )/60,2) as posted')
            ->where('date_posted', '=', date('Y-m-d'))
            ->orWhere('date_posted', '=', $saturday)
            ->groupBy('shipment_date', 'ship_to_code', 'ship_to_city', 'customer_name', 'order_number', 'sage_sales_order', 'purchase_order_number', 'scac', 'bol_pro', 'posted')
            ->orderBy('order_number', 'ASC')->get();
    }

    public static function getEndOfDayShippedOrders()
    {
        $saturday = self::getSaturday();

        return self::select('shipment_date', 'ship_to_code', 'ship_to_city', 'customer_name', 'order_number', 'sage_sales_order', 'purchase_order_number', 'scac', 'bol_pro')
            ->selectRaw('timestampdiff(SECOND, time_posted, CURRENT_TIMESTAMP ) as posted')
            ->selectRaw('SUM(quantity_shipped) as quantity_shipped')
            ->where('date_posted', '=', date('Y-m-d'))
            ->orWhere('date_posted', '=', $saturday)
            ->having('quantity_shipped', '>', 0)
            ->groupBy('shipment_date', 'ship_to_code', 'ship_to_city', 'customer_name', 'order_number', 'sage_sales_order', 'purchase_order_number', 'scac', 'bol_pro', 'posted')
            ->orderBy('order_number', 'ASC')
            ->get();
    }

    public static function getPostedOrderNumbers()
    {
        return self::select('order_number')
            ->where('date_posted', '=', date('Y-m-d'))
            ->groupBy('order_number')
            ->get();
    }

    public static function getShippedToday($sage_sales_order, $po_line, $product_code)
    {
        return self::where('sage_sales_order', $sage_sales_order)
            ->where('po_line', $po_line)
            ->where('product_code', $product_code)
            ->where('date_posted', '=', date('Y-m-d'))
            ->first();
    }

    public static function getNotInvoicedCount()
    {
        $saturday = self::getSaturday();
        $invoices = new MAS_SO_InvoiceHeader();
        $invoices = $invoices->getInvoiceList();

        return self::select('sage_sales_order')
            ->where('date_posted', '>=', $saturday)
            ->where('date_posted', '<=', date('Y-m-d'))
            ->whereNotIn('sage_sales_order', $invoices->pluck('SalesOrderNo'))
            ->groupBy('sage_sales_order')
            ->get()
            ->count();
    }

    public static function getInvoiceMismatchCount(): int
    {
        $invoice_header = new MAS_SO_InvoiceHeader();
        $x = 0;
        $posted_shipments_detail = self::getShippedOrdersDetail();
        foreach ($posted_shipments_detail as $row) {
            $invoice = $invoice_header->checkInvoiceDetail($row->sage_sales_order, round($row->po_line, 1), $row->product_code);

            if (!isset($invoice)) {
                $invoice = new stdClass();
                $invoice->QuantityShipped = '0';
                $invoice->InvoiceNo = '';
                $row->QuantityShipped = '0';
            }
            if (round($row->quantity_shipped, 1) != round($invoice->QuantityShipped, 1)) {
                $x++;
            }
            unset($invoice);
        }
        return $x;
    }

    public static function getShippedOrdersDetail()
    {
        $saturday = self::getSaturday();

        return self::select('order_number', 'sage_sales_order', 'product_code', 'po_line', 'warehouse_code')
            ->selectRaw('SUM(quantity_allocated) as quantity_shipped')
            ->where('date_posted', '>=', $saturday)
            ->where('date_posted', '<=', date('Y-m-d'))
            ->where('product_code', 'not like', '/%')
            ->orderBy('order_number', 'asc')
            ->groupBy('order_number', 'sage_sales_order', 'product_code', 'po_line', 'warehouse_code')
            ->get();
    }

    public static function getWarehouseMismatchCount(): int
    {
        $invoice_header = new MAS_SO_InvoiceHeader();
        $x = 0;
        foreach (self::getShippedOrdersDetail() as $row) {
            $invoice = $invoice_header->checkInvoiceDetail($row->sage_sales_order, round($row->po_line, 1), $row->product_code);
            if (isset($invoice)) {
                if ($row->warehouse_code != $invoice->WarehouseCode && $row->quantity_shipped >= 1 && $invoice->InvoiceNo <> '') {
                    $x++;
                }
            }
            unset($invoice);
        }
        return $x;
    }

    public static function getPostedCount(): array
    {
        $saturday = self::getSaturday();

        $posted = self::select('order_number')
            ->where('date_posted', '>=', $saturday)
            ->where('date_posted', '<=', date('Y-m-d'))
            ->where('quantity_shipped', '>', '0')
            ->groupBy('order_number')
            ->get()
            ->count();

        $cancelled = self::select('order_number')
            ->where('date_posted', '>=', $saturday)
            ->where('date_posted', '<=', date('Y-m-d'))
            ->where('quantity_shipped', '<', '0')
            ->groupBy('order_number')
            ->get()
            ->count();

        return array("posted" => $posted, "cancelled" => $cancelled, "total" => $posted - $cancelled);
    }

    public static function getEndOfDayDashboardInfo(): array
    {
        $saturday = self::getSaturday();

        $posted = self::select('order_number')
            ->where('date_posted', '>=', $saturday)
            ->where('date_posted', '<=', date('Y-m-d'))
            ->where('quantity_shipped', '>', '0')
            ->groupBy('order_number')
            ->get()
            ->count();

        $cancelled = self::select('order_number')
            ->where('date_posted', '>=', $saturday)
            ->where('date_posted', '<=', date('Y-m-d'))
            ->where('quantity_shipped', '<', '0')
            ->groupBy('order_number')
            ->get()
            ->count();

        return array("sphere_posted" => $posted, "sphere_cancelled" => $cancelled, "sphere_total" => $posted - $cancelled);
    }

    public static function getPostedLines()
    {
        $saturday = self::getSaturday();

        return self::select('product_code')
            ->where('date_posted', '>=', $saturday)
            ->where('date_posted', '<=', date('Y-m-d'))
            ->where('product_code', 'not like', '/%')
            ->where('quantity_shipped', '>', '0')
            ->count();
    }


    public static function getSaturday(): string
    {
        try {
            $saturday = new DateTime(date('Y-m-d'));
            return (date('N') == 1 ? $saturday->modify('-2 days')->format('Y-m-d') : date('Y-m-d'));
        } catch (Exception $e) {
            return date('Y-m-d');
        }
    }

    public static function getLastRecord()
    {
        return self::select('time_posted')
            ->orderByDesc('time_posted')
            ->limit(1)
            ->first();
    }
}
