<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CN_ReceiptHeader extends Model
{
    protected $connection = 'digismart';
    protected $table      = 'CN_ReceiptHeader';
    protected $fillable   = ['invoice_number', 'invoice_receipt', 'test_id', 'test', 'user'];

    public static function listReceiptDetail($id)
    {
        $header = self::where('id', '=', $id)->first();

        $receipts = $header->listAllReceiptDetails;


        $po_list = CN_POList::listPurchaseOrders($header->invoice_number);
        $tested_header = CN_TestHeader::find($header->test_id);
        $tested = $tested_header->listTestData;

        $fields = array();
        $po = array();
        $fields[] = [
            "key" => "product_code",
            "label" => "Product Code",
            "sortable" => false,
            "thClass" => "text-nowrap small",
            "tdClass" => "text-nowrap small",

        ];
        $fields[] = [
            "key" => "tested",
            "label" => "Tested",
            "sortable" => false,
            "thClass" => "text-nowrap text-center small",
            "tdClass" => "text-nowrap text-center small"
        ];

        $x = 0;
        foreach ($po_list as $line) {
            $fields[] = [
                "key" => "po" . $x,
                "label" => "" . $line->purchase_order . "",
                "sortable" => false,
                "tdClass" => "p-0"
            ];
            $x++;
        }

        $rowData = [];

        for ($x = 0; $x < count($po_list); $x++) {
            $po[$x] = $po_list[$x]->purchase_order;
        }


        for ($x = 0; $x < count($tested); $x++) {
            $row_total = 0;
            $row_received = 0;
            for ($y = 0; $y < count($po); $y++) {
                $master = CN_Master::getTestPoQty($line->invoice_number, $line->product_code, $po[$y]);

                $receipt = $header->listReceiptQuantity($line->product_code, $po[$y]);

                if ($master) {
                    $row_total = $row_total + $master['quantity'];
                    $row_received = $row_received + $receipt->quantity;
                    $rowData[$x]["po$y"] = $receipt->quantity;
                    $rowData[$x]["po$y" . "_key"] = $master->line;
                } else {
                    $rowData[$x]["po$y"] = "";
                    $rowData[$x]["po$y" . "_key"] = null;
                }
            }


        }
        return $rowData;
        /*
        foreach ($tested as $line) {
            $rowTotal = 0;
            $rowReceived = 0;
            for ($y = 0; $y < count($po); $y++) {

            }
            $rowData[$x]['product_code'] = $line->productCode;
            $rowData[$x]['row_total'] = $rowTotal;
            $rowData[$x]['tested'] = $line->qtyTested;
            $rowData[$x]['received'] = $rowReceived;
            $rowData[$x]['error_tested_vs_received'] = $line->qtyTested != $rowReceived;
            $rowData[$x]['error_tested_vs_total'] = $rowTotal != $line->qtyTested;
            $x++;
        }






        return array('fields' => $fields);
        */
    }

    public function listPurchaseOrders()
    {
        return $this->hasMany(CN_POList::class, 'invoice_number', 'invoice_number')->select('purchase_order')->orderBy('purchase_order');
    }

    public function listReceiptQuantity($product_code, $purchase_order)
    {
        return $this->hasOne(CN_ReceiptDetail::class, 'receipt_id', 'id')->where('product_code', '=', $product_code)->where('purchase_order', '=', $purchase_order);
    }

    public function invoiceHeader()
    {
        return $this->belongsTo(CN_Header::class, 'invoice_number', 'invoice_number');
    }

    public function listAllReceiptDetails()
    {
        return $this->hasMany(CN_ReceiptDetail::class, 'receipt_id', 'id');
    }

    public function listReceiptDetails()
    {
        return $this->hasMany(CN_ReceiptDetail::class, 'receipt_id', 'id')
            ->select('master_id', 'product_code', 'purchase_order')
            ->selectRaw('SUM(quantity) as quantity')
            ->groupBy('product_code', 'master_id', 'purchase_order')
            ->where('quantity', '>', 0);
    }

    public function getInvoiceTestNumber()
    {
        return $this->hasOne(CN_TestHeader::class, 'id', 'test_id');
    }


}
