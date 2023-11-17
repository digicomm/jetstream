<?php

namespace App\Models;

use ErrorException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use stdClass;

class SP_OpenARNs extends Model
{
    protected $connection = 'digismart';
    protected $table      = 'SP_OpenARNs';

    public static function getOpenARNs()
    {
        return self::select('arn_number', 'date_entered', 'date_expected', 'purchase_order', 'supplier_name', 'secondary_reference', 'po_line', 'line_number', 'product_code', 'quantity_expected', 'status', 'special_instructions')
            ->orderBy('arn_number')
            ->orderBy('po_line')
            ->groupBy('arn_number', 'date_entered', 'date_expected', 'purchase_order', 'supplier_name', 'secondary_reference', 'po_line', 'line_number', 'product_code', 'quantity_expected', 'status', 'special_instructions')
            ->get();
    }

    public static function getBackorderARNs()
    {
        return self::select('arn_number', 'date_entered', 'date_expected', 'purchase_order', 'supplier_name', 'secondary_reference', 'po_line', 'line_number', 'product_code', 'quantity_expected', 'status', 'special_instructions')
            //->where('supplier_name', 'like','Cisco%')
            ->orderBy('arn_number')
            ->orderBy('po_line')
            ->groupBy('arn_number', 'date_entered', 'date_expected', 'purchase_order', 'supplier_name', 'secondary_reference', 'po_line', 'line_number', 'product_code', 'quantity_expected', 'status', 'special_instructions')
            ->get();
    }

    public static function getVerifyARNs()
    {
        try {
            return self::leftJoin('data.asnIncoming', 'asnIncoming.secRef', '=', 'SP_OpenARNs.secondary_reference')
                ->whereNull('asnIncoming.subLine')
                ->where('supplier_name', 'like', 'Cisco%')
                ->select('arn_number', 'date_entered', 'date_expected', 'purchase_order', 'supplier_name', 'secondary_reference', 'po_line', 'line_number', 'product_code', 'quantity_expected', 'asnIncoming.shipped', 'SP_OpenARNs.status')
                ->orderBy('arn_number')
                ->orderBy('po_line')
                ->groupBy('arn_number', 'date_entered', 'date_expected', 'purchase_order', 'supplier_name', 'secondary_reference', 'po_line', 'line_number', 'product_code', 'quantity_expected', 'asnIncoming.shipped', 'SP_OpenARNs.status')
                ->get();
        } catch (QueryException $e) {
            sleep(2);
            return self::leftJoin('data.asnIncoming', 'asnIncoming.secRef', '=', 'SP_OpenARNs.secondary_reference')
                ->whereNull('asnIncoming.subLine')
                ->where('supplier_name', 'like', 'Cisco%')
                ->select('arn_number', 'date_entered', 'date_expected', 'purchase_order', 'supplier_name', 'secondary_reference', 'po_line', 'line_number', 'product_code', 'quantity_expected', 'asnIncoming.shipped', 'SP_OpenARNs.status')
                ->orderBy('arn_number')
                ->orderBy('po_line')
                ->groupBy('arn_number', 'date_entered', 'date_expected', 'purchase_order', 'supplier_name', 'secondary_reference', 'po_line', 'line_number', 'product_code', 'quantity_expected', 'asnIncoming.shipped', 'SP_OpenARNs.status')
                ->get();
        }
    }

    public static function getInTransit($purchase_order, $po_line)
    {
        return self::selectRaw('sum(quantity_expected) as quantity')
            ->where('purchase_order', $purchase_order)
            ->where('po_line', $po_line)
            ->groupBy('purchase_order', 'po_line')
            ->first();
    }

    public static function asnCheck($secRef)
    {
        return self::select('secondary_reference')->where('secondary_reference', $secRef)->get();
    }

    public static function getOpenARNsGM()
    {
        return self::select('itemCode')
            ->selectRaw('SUM(qtyExpected) as qtyExpected')
            ->groupBy('itemCode')
            ->orderBy('itemCode')
            ->get();
    }

    public static function getNodeSplit($itemCode)
    {
        try {
            return self::select('item_code')
                ->selectRaw('SUM(quantity_expected) as quantity_expected')
                ->groupBy('item_code')
                ->where('item_code', $itemCode)
                ->first();
        } catch (ErrorException $e) {
            $arn = new stdClass();
            $arn->quantity_expected = '0';
            return $arn;
        }
    }

    public static function getBackorderReport($item_code)
    {
        return self::select('product_code')
            ->selectRaw('SUM(quantity_expected) as quantity_expected')
            ->where('product_code', $item_code)
            ->groupBy('product_code')
            ->orderBy('product_code')
            ->first();
    }

    public static function getRdof($itemCode)
    {
        try {
            return self::select('itemCode')
                ->selectRaw('SUM(qtyExpected) as qtyExpected')
                ->groupBy('itemCode')
                ->where('itemCode', $itemCode)
                ->first();
        } catch (ErrorException $e) {
            $arn = new stdClass();
            $arn->qtyExpected = '0';
            return $arn;
        }
    }

    public static function getExpected()
    {
        return self::select('item_code')
            ->selectRaw('SUM(quantity_expected) as quantity_expected')
            ->groupBy('item_code')
            ->orderBy('item_code')
            ->get();
    }

}
