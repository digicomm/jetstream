<?php

namespace App\Models;

use App\Casts\FifoDate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;

class SP_InventoryOnHand extends Model
{
    protected $connection = 'digismart';
    protected $table      = 'SP_InventoryOnHand';
    protected $primaryKey = 'uid';
    protected $casts      = ['quantity_on_hand' => 'integer', 'quantity_available' => 'integer', 'fifo_lifo' => FifoDate::class,];

    /*
     * Adjustments
     */
    public static function getAdjItemQtyAvailable($productCode, $warehouseCode, $binLocation)
    {
        return self::where('product_code', 'like', $productCode)->where('bin_location', 'like', $binLocation)->where('warehouse_code', 'like', $warehouseCode)->where('tag_serial_number', '')->sum('quantity_available');
    }

    public static function getAdjItemRecords($productCode, $warehouseCode, $binLocation)
    {
        return self::select('product_code', 'warehouse_code', 'fifo_lifo', 'bin_location', 'lot_number', 'tag_serial_number')->selectRaw('SUM(quantity_available) as quantity_available')->where('product_code', 'like', $productCode)->where('bin_location', 'like', $binLocation)->where('warehouse_code', 'like', $warehouseCode)->where('tag_serial_number', '')->having('quantity_available', '>', '0')->groupBy('product_code', 'warehouse_code', 'bin_location', 'fifo_lifo', 'lot_number', 'tag_serial_number')->orderBy('fifo_lifo')->get();
    }

    public static function getAdjSerialQtyAvailable($productCode, $warehouseCode, $binLocation, $tagSerialNumber)
    {
        return self::where('product_code', 'like', $productCode)
            ->where('bin_location', 'like', $binLocation)
            ->where('warehouse_code', 'like', $warehouseCode)
            ->where('tag_serial_number', 'like', $tagSerialNumber)
            ->sum('quantity_available');
    }

    public static function getAdjSerialRecords($productCode, $warehouseCode, $binLocation, $tagSerialNumber)
    {
        return self::select('product_code', 'warehouse_code', 'fifo_lifo', 'bin_location', 'lot_number', 'tag_serial_number')->selectRaw('SUM(quantity_available) as quantity_available')->where('product_code', 'like', $productCode)->where('bin_location', 'like', $binLocation)->where('warehouse_code', 'like', $warehouseCode)->where('tag_serial_number', 'like', $tagSerialNumber)->having('quantity_available', '>', '0')->groupBy('product_code', 'warehouse_code', 'bin_location', 'fifo_lifo', 'lot_number', 'tag_serial_number')->orderBy('fifo_lifo')->get();
    }

    public static function getAdjMiscQtyAvailable($productCode, $warehouseCode, $binLocation)
    {
        return self::where('product_code', 'like', $productCode)->where('bin_location', 'like', $binLocation)->where('warehouse_code', 'like', $warehouseCode)->sum('quantity_available');
    }

    public static function getAdjMiscRecords($productCode, $warehouseCode, $binLocation)
    {
        return self::select('product_code', 'warehouse_code', 'fifo_lifo', 'bin_location', 'lot_number', 'tag_serial_number')->selectRaw('SUM(quantity_available) as quantity_available')->where('product_code', 'like', $productCode)->where('bin_location', 'like', $binLocation)->where('warehouse_code', 'like', $warehouseCode)->having('quantity_available', '>', '0')->groupBy('product_code', 'warehouse_code', 'bin_location', 'fifo_lifo', 'lot_number', 'tag_serial_number')->orderBy('fifo_lifo')->get();
    }

    public static function getAdjMiscQtySerialAvailable($productCode, $warehouseCode, $binLocation, $tagSerialNumber)
    {
        return self::where('product_code', 'like', strtoupper($productCode))->where('bin_location', 'like', $binLocation)->where('warehouse_code', 'like', $warehouseCode)->where('tag_serial_number', 'like', $tagSerialNumber)->sum('quantity_available');
    }

    public static function getAdjMiscSerialRecords($productCode, $warehouseCode, $binLocation, $tagSerialNumber)
    {
        return self::select('product_code', 'warehouse_code', 'fifo_lifo', 'bin_location', 'lot_number', 'tag_serial_number')->selectRaw('SUM(quantity_available) as quantity_available')->where('product_code', 'like', $productCode)->where('bin_location', 'like', $binLocation)->where('warehouse_code', 'like', $warehouseCode)->where('tag_serial_number', 'like', $tagSerialNumber)->having('quantity_available', '>', '0')->groupBy('product_code', 'warehouse_code', 'bin_location', 'fifo_lifo', 'lot_number', 'tag_serial_number')->orderBy('fifo_lifo')->get();
    }


    /*
     * Transfers
     */
    public static function getBinTransferQtyAvailable($productCode, $warehouseCode, $binLocation)
    {
        return self::where('product_code', 'like', $productCode)->where('bin_location', 'like', $binLocation)->where('warehouse_code', 'like', $warehouseCode)->sum('quantity_available');
    }

    public static function getBinTransferRecords($productCode, $warehouseCode, $binLocation)
    {
        return self::select('product_code', 'warehouse_code', 'fifo_lifo', 'bin_location', 'lot_number', 'tag_serial_number')->selectRaw('SUM(quantity_available) as quantity_available')->where('product_code', 'like', strtoupper($productCode))->where('bin_location', 'like', strtoupper($binLocation))->where('warehouse_code', 'like', $warehouseCode)->having('quantity_available', '>', '0')->groupBy('product_code', 'warehouse_code', 'bin_location', 'fifo_lifo', 'lot_number', 'tag_serial_number')->orderBy('fifo_lifo')->orderBy('bin_location')->get();
    }

    public static function getBinTransferSerialQtyAvailable($productCode, $warehouseCode, $binLocation, $tagSerialNumber)
    {
        return self::where('product_code', 'like', strtoupper($productCode))->where('bin_location', strtoupper($binLocation))->where('warehouse_code', $warehouseCode)->where('tag_serial_number', strtoupper($tagSerialNumber))->sum('quantity_available');
    }

    public static function getBinTransferSerialRecords($productCode, $warehouseCode, $binLocation, $tagSerialNumber)
    {
        return self::select('product_code', 'warehouse_code', 'fifo_lifo', 'bin_location', 'lot_number', 'tag_serial_number')->selectRaw('SUM(quantity_available) as quantity_available')->where('product_code', 'like', strtoupper($productCode))->where('bin_location', 'like', strtoupper($binLocation))->where('warehouse_code', 'like', $warehouseCode)->where('tag_serial_number', 'like', strtoupper($tagSerialNumber))->having('quantity_available', '>', '0')->groupBy('product_code', 'warehouse_code', 'bin_location', 'fifo_lifo', 'lot_number', 'tag_serial_number')->orderBy('fifo_lifo')->orderBy('bin_location')->get();
    }

    public static function getWarehouseTransferRecords($productCode, $warehouseCodes)
    {
        return self::select('product_code', 'warehouse_code', 'fifo_lifo', 'bin_location', 'lot_number', 'tag_serial_number')->selectRaw('SUM(quantity_available) as quantity_available')->where('product_code', 'like', $productCode)->whereIn('warehouse_code', $warehouseCodes)->having('quantity_available', '>', '0')->groupBy('product_code', 'warehouse_code', 'bin_location', 'fifo_lifo', 'lot_number', 'tag_serial_number')->orderBy('fifo_lifo')->get();

    }

    public static function getMoveGigaXtend()
    {
        return self::select('product_code', 'warehouse_code', 'fifo_lifo', 'bin_location', 'lot_number', 'tag_serial_number')->selectRaw('SUM(quantity_available) as quantity_available')->where(function ($query) {
            $query->where('product_code', 'like', 'GAGMLES2_')->orWhere('product_code', 'like', 'GAGMLES4_')->orWhere('product_code', 'like', 'GAGMLES4__')->orWhere('product_code', 'like', 'GAGMLES8_')->orWhere('product_code', 'like', 'GAGMSADS2_')->orWhere('product_code', 'like', 'GAGMSATS2_');
        })->whereNotIn('warehouse_code', ['ASK', 'QUA', 'RDO', 'RMA', 'S&D', 'S/O', 'SAM', 'TEC', 'USE'])->groupBy('product_code', 'warehouse_code', 'bin_location', 'fifo_lifo', 'lot_number', 'tag_serial_number')->having('quantity_available', '>', '0')->orderBy('fifo_lifo')->get();
    }

    public static function getMoveCisco()
    {
        $list = DS_MacroMoveLists::find('CISCO');
        $items = $list->getListItems;
        return self::select('product_code', 'warehouse_code', 'fifo_lifo', 'bin_location', 'lot_number', 'tag_serial_number')->selectRaw('SUM(quantity_available) as quantity_available')->whereIn('product_code', $items->pluck('product_code'))->where('bin_location', 'not like', 'TECLAB')->whereNotIn('warehouse_code', ['001', 'ASK', 'CHI', 'QUA', 'RDO', 'RMA', 'S&D', 'S/O', 'SAM', 'TEC', 'USE'])->groupBy('product_code', 'warehouse_code', 'bin_location', 'fifo_lifo', 'lot_number', 'tag_serial_number')->having('quantity_available', '>', '0')->orderBy('fifo_lifo')->limit(2500)->get();
    }


    /*
     * Issues
     */
    public static function getIssueQtyAvailable($productCode, $warehouseCode, $binLocation)
    {
        return self::where('product_code', 'like', $productCode)
            ->where('bin_location', 'like', $binLocation)
            ->where('warehouse_code', 'like', $warehouseCode)
            ->sum('quantity_available');
    }

    public static function getIssueRecords($productCode, $warehouseCode, $binLocation)
    {
        return self::select('product_code', 'warehouse_code', 'fifo_lifo', 'bin_location', 'lot_number', 'tag_serial_number')->selectRaw('SUM(quantity_available) as quantity_available')->where('product_code', 'like', $productCode)->where('bin_location', 'like', $binLocation)->where('warehouse_code', 'like', $warehouseCode)->having('quantity_available', '>', '0')->groupBy('product_code', 'warehouse_code', 'bin_location', 'fifo_lifo', 'lot_number', 'tag_serial_number')->orderBy('fifo_lifo')->get();
    }

    public static function getIssueQtySerialAvailable($productCode, $warehouseCode, $binLocation, $tagSerialNumber)
    {
        return self::where('product_code', 'like', strtoupper($productCode))->where('bin_location', 'like', $binLocation)->where('warehouse_code', 'like', $warehouseCode)->where('tag_serial_number', 'like', $tagSerialNumber)->sum('quantity_available');
    }

    public static function getIssueSerialRecords($productCode, $warehouseCode, $binLocation, $tagSerialNumber)
    {
        return self::select('product_code', 'warehouse_code', 'fifo_lifo', 'bin_location', 'lot_number', 'tag_serial_number')->selectRaw('SUM(quantity_available) as quantity_available')->where('product_code', 'like', $productCode)->where('bin_location', 'like', $binLocation)->where('warehouse_code', 'like', $warehouseCode)->where('tag_serial_number', 'like', $tagSerialNumber)->having('quantity_available', '>', '0')->groupBy('product_code', 'warehouse_code', 'bin_location', 'fifo_lifo', 'lot_number', 'tag_serial_number')->orderBy('fifo_lifo')->get();
    }


    /*
     * Cycle Counts
     */
    public static function getCycleCount()
    {
        return self::select('product_code', 'warehouse_code', 'bin_location')->selectRaw('SUM(`quantity_on_hand`) AS quantity_on_hand, SUM(`quantity_allocated`) AS quantity_allocated')->whereIn('product_code', request()->json()->all()['product_codes'])->groupBy('product_code')->groupBy('warehouse_code')->groupBy('bin_location')->orderBy('bin_location', 'ASC')->orderBy('warehouse_code', 'ASC')->get();
    }

    public static function getCycleCountRefresh($products)
    {
        return self::select('product_code', 'warehouse_code', 'bin_location')->selectRaw('SUM(`quantity_on_hand`) AS quantity_on_hand, SUM(`quantity_allocated`) AS quantity_allocated')->whereIn('product_code', $products)->groupBy('product_code')->groupBy('warehouse_code')->groupBy('bin_location')->orderBy('bin_location', 'ASC')->orderBy('warehouse_code', 'ASC')->get();
    }

    /*
     * Inventory
     */
    public static function getInventoryByBin($binLocation)
    {
        if (isset($binLocation) && $binLocation <> "") {
            return self::select('SP_InventoryOnHand.product_code', 'SP_InventoryOnHand.warehouse_code', 'SP_InventoryOnHand.bin_location', 'SP_LastTransaction.last_shipment', 'SP_LastTransaction.last_adjustment')->selectRaw('CI_Item.Category1 as manufacturer')->selectRaw('SUM(SP_InventoryOnHand.quantity_on_hand) as quantity_on_hand, SUM(SP_InventoryOnHand.quantity_allocated) as quantity_allocated, SUM(SP_InventoryOnHand.quantity_available) as quantity_available, MAX(STR_TO_DATE(fifo_lifo, \'%m/%d/%Y\')) as fifo_lifo')->groupBy('SP_InventoryOnHand.product_code', 'SP_InventoryOnHand.warehouse_code', 'SP_InventoryOnHand.bin_location')->orderBy('SP_InventoryOnHand.bin_location')->orderBy('SP_InventoryOnHand.product_code')->orderBy('SP_InventoryOnHand.warehouse_code')->where('SP_InventoryOnHand.bin_location', 'like', strtoupper($binLocation))->leftJoin('CI_Item', 'SP_InventoryOnHand.product_code', '=', 'CI_Item.ItemCode')->leftJoin('SP_LastTransaction', 'SP_InventoryOnHand.product_code', '=', 'SP_LastTransaction.product_code')->get();
        } else {
            return '';
        }
    }

    public static function getInventoryByAisle($aisle)
    {
        if (isset($aisle) && $aisle <> "") {
            return self::select('SP_InventoryOnHand.product_code', 'SP_InventoryOnHand.warehouse_code', 'SP_InventoryOnHand.bin_location', 'SP_LastTransaction.last_shipment', 'SP_LastTransaction.last_adjustment')
                ->selectRaw('CI_Item.Category1 AS manufacturer, SUM(SP_InventoryOnHand.quantity_on_hand) as quantity_on_hand, SUM(SP_InventoryOnHand.quantity_allocated) as quantity_allocated, SUM(SP_InventoryOnHand.quantity_available) as quantity_available, MAX(STR_TO_DATE(fifo_lifo, \'%m/%d/%Y\')) as fifo_lifo')->groupBy('SP_InventoryOnHand.product_code', 'SP_InventoryOnHand.warehouse_code', 'SP_InventoryOnHand.bin_location')->orderBy('SP_InventoryOnHand.bin_location')->orderBy('SP_InventoryOnHand.product_code')->orderBy('SP_InventoryOnHand.warehouse_code')->where('bin_location', 'like', strtoupper($aisle . '%'))->leftJoin('CI_Item', 'SP_InventoryOnHand.product_code', '=', 'CI_Item.ItemCode')->leftJoin('SP_LastTransaction', 'SP_InventoryOnHand.product_code', '=', 'SP_LastTransaction.product_code')->get();
        } else {
            return '';
        }
    }

    public static function getInventoryByProduct($productCode)
    {
        if (isset($productCode) && $productCode <> "") {
            return self::select('SP_InventoryOnHand.product_code', 'SP_InventoryOnHand.warehouse_code', 'SP_InventoryOnHand.bin_location', 'CI_Item.ItemCodeDesc')->selectRaw('SUM(SP_InventoryOnHand.quantity_on_hand) as quantity_on_hand, SUM(SP_InventoryOnHand.quantity_allocated) as quantity_allocated, SUM(SP_InventoryOnHand.quantity_available) as quantity_available')->leftJoin('digismart.CI_Item', 'SP_InventoryOnHand.product_code', '=', 'CI_Item.ItemCode')->groupBy('SP_InventoryOnHand.product_code', 'SP_InventoryOnHand.warehouse_code', 'SP_InventoryOnHand.bin_location')->orderBy('SP_InventoryOnHand.product_code')->orderBy('SP_InventoryOnHand.bin_location')->orderBy('SP_InventoryOnHand.warehouse_code')->where('SP_InventoryOnHand.product_code', 'like', strtoupper($productCode))->get();
        } else {
            return '';
        }
    }


    /*
     * Builds
     */
    public static function getBuildsAvailable($productCode, $warehouseCode, $binLocation, $tagSerialNumber, $quantity)
    {
        return self::select('product_code', 'warehouse_code', 'fifo_lifo', 'bin_location', 'tag_serial_number')->selectRaw('SUM(quantity_available) as QAV')->groupBy('product_code', 'warehouse_code', 'bin_location', 'fifo_lifo', 'tag_serial_number')->where('product_code', '=', $productCode)->where('warehouse_code', '=', $warehouseCode)->where('bin_location', '=', $binLocation)->where('tag_serial_number', '=', $tagSerialNumber)->having('QAV', '>=', $quantity)->orderBy('fifo_lifo')->get();
    }

    public static function getBuildsAllAvailable($productCode, $warehouseCode, $binLocation)
    {
        return self::select('product_code', 'warehouse_code', 'fifo_lifo', 'bin_location')->selectRaw('SUM(quantity_available) as QAV')->groupBy('product_code', 'warehouse_code', 'bin_location', 'fifo_lifo')->where('product_code', '=', $productCode)->where('warehouse_code', '=', $warehouseCode)->where('bin_location', '=', $binLocation)->having('QAV', '>', '0')->orderBy('fifo_lifo')->get();
    }

    public static function getBuildsQtyAvailable($productCode, $warehouseCode, $binLocation, $tagSerialNumber)
    {
        return self::where('product_code', '=', $productCode)->where('warehouse_code', '=', $warehouseCode)->where('tag_serial_number', '=', $tagSerialNumber)->where('bin_location', '=', $binLocation)->sum('quantity_available');
    }


    public static function getVarianceReportData()
    {
        return self::select('product_code', 'warehouse_code')->selectRaw('SUM(quantity_on_hand) AS quantity_on_hand')->groupBy('product_code', 'warehouse_code')->orderBy('product_code', 'asc')->orderBy('warehouse_code', 'asc')->get();
    }


    public static function getUsedBinLocations()
    {
        return self::select('bin_location')->where('quantity_on_hand', '>', '0')->where('bin_location', '<>', '')->distinct()->get();
    }

    public static function getAllQUA()
    {
        return self::select('uid', 'product_code', 'fifo_lifo', 'bin_location', 'tag_serial_number', 'quantity_on_hand')->where('warehouse_code', 'QUA')->get();
    }

    public static function getAllQUAList()
    {
        return self::select('uid')->where('warehouse_code', 'QUA')->get();
    }

    public static function getNewQUA($existing)
    {
        return self::select('uid', 'product_code', 'fifo_lifo', 'bin_location', 'tag_serial_number', 'quantity_on_hand')->where('warehouse_code', 'QUA')->whereNotIn('uid', $existing)->get();
    }

    public static function getExistingQUA($existing)
    {
        return self::select('uid', 'product_code', 'fifo_lifo', 'bin_location', 'tag_serial_number', 'quantity_on_hand')->whereIn('uid', $existing)->get();
    }

    public static function getLabBuildsOnHand($productCode)
    {
        return self::where('product_code', $productCode)->whereNotIn('warehouse_code', ['ASK', 'QUA', 'RMA', 'S&D', 'SAM', 'USE', 'TEC'])->groupBy('product_code')->sum('quantity_available');
    }


    /*
     * Backorders
     */
    public static function getBackorderAvailable($productCode, $excludedWarehouses = ['ASK', 'N/A', 'QUA', 'RMA', 'S&D', 'SAM', 'TEC', 'USE'])
    {
        return self::select('product_code')->selectRaw('SUM(quantity_on_hand) as quantity_on_hand, SUM(quantity_available) as quantity_available')->where('product_code', $productCode)->whereNotIn('warehouse_code', $excludedWarehouses)->groupBy('product_code')->orderBy('product_code')->first();
    }

    public static function getBestWarehouse($productCode, $excludedWarehouses = ['ASK', 'N/A', 'QUA', 'RMA', 'S&D', 'SAM', 'TEC', 'USE'])
    {
        try {
            return self::select('warehouse_code')->selectRaw('SUM(quantity_available) as quantity_available')->where('product_code', $productCode)->whereNotIn('warehouse_code', $excludedWarehouses)->having('quantity_available', '>', '0')->groupBy('product_code', 'warehouse_code')->orderBy('quantity_available', 'desc')->first();
        } catch (QueryException) {
            return null;
        }
    }

    public static function getOnHandByWarehouse($productCode, $warehouseCode)
    {
        return self::select('product_code', 'quantity_available')->where('product_code', '=', $productCode)->where('warehouse_code', '=', $warehouseCode)->groupBy('product_code', 'warehouse_code')->sum('quantity_available');
    }

    public static function getKitComponentInventory($productCode)
    {
        return self::where('product_code', '=', $productCode)
            ->whereIn('warehouse_code', ['000', 'ADE', 'GDE'])
            ->sum('quantity_available');
    }
}
