<?php

namespace App\Console\Commands;

use App\Models\DS_VarianceReport;
use App\Models\MAS_IM_ItemWarehouse;
use App\Models\SP_InventoryOnHand;
use Illuminate\Console\Command;

class InventoryVarianceReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:inventory-variance-report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh Inventory Variance Report';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $update = array();
        $sage = MAS_IM_ItemWarehouse::getVarianceReportData();
        $wms = SP_InventoryOnHand::getVarianceReportData();
        DS_VarianceReport::truncate();
        $list = array();

        foreach ($sage as $row) {
            $list[strtoupper($row->ItemCode) . '^' . $row->WarehouseCode]['product_code'] = $row->ItemCode;
            $list[strtoupper($row->ItemCode) . '^' . $row->WarehouseCode]['warehouse_code'] = $row->WarehouseCode;
            $list[strtoupper($row->ItemCode) . '^' . $row->WarehouseCode]['sage'] = intval($row->OnHand);
            $list[strtoupper($row->ItemCode) . '^' . $row->WarehouseCode]['wms'] = 0;
        }

        foreach ($wms as $row) {
            $list[strtoupper($row->product_code) . '^' . $row->warehouse_code]['product_code'] = $row->product_code;
            $list[strtoupper($row->product_code) . '^' . $row->warehouse_code]['warehouse_code'] = $row->warehouse_code;
            $list[strtoupper($row->product_code) . '^' . $row->warehouse_code]['sage'] = isset($list[strtoupper($row->product_code) . '^' . $row->warehouse_code]['sage']) ? $list[strtoupper($row->product_code) . '^' . $row->warehouse_code]['sage'] : 0;
            $list[strtoupper($row->product_code) . '^' . $row->warehouse_code]['wms'] = intval($row->quantity_on_hand);
        }

        foreach ($list as $row) {
            if ($row['sage'] !== $row['wms']) {
                $update[] = ['product_code' => $row['product_code'], 'warehouse_code' => $row['warehouse_code'], 'sage' => $row['sage'], 'wms' => $row['wms']];
            }
        }
        $chunks = array_chunk($update, 100);

        foreach ($chunks as $chunk) {
            DS_VarianceReport::upsert($chunk, ['product_code', 'warehouse'], ['sage', 'wms']);
        }
    }
}
