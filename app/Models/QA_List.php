<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QA_List extends Model
{
    use SoftDeletes;

    protected $connection = 'digismart';
    protected $table      = 'QA_List';
    protected $primaryKey = 'uid';

    public static function getAll()
    {
        return self::select('uid')->get();
    }

    public static function getByItem($product_code)
    {
        return self::select('QA_List.*')
            ->selectRaw('LS_QUAType.type as type_text, LS_QUAStatus.status as status_text')
            ->where('product_code', '=', $product_code)
            ->where('QA_List.status', '<', '3')
            ->leftJoin('LS_QUAType', 'QA_List.type', '=', 'LS_QUAType.id')
            ->leftJoin('LS_QUAStatus', 'QA_List.status', '=', 'LS_QUAStatus.id')
            ->get();
    }

    public static function getExistingList()
    {
        return self::select('uid', 'qtyOnHand', 'binLocation')->where('status', '<', '3')->get();
    }

    public static function getFixList()
    {
        return self::select('uid', 'quantity_on_hand', 'bin_location', 'previous_status')
            ->where('status', '3')->get();
    }

    public static function getRemoved($current)
    {
        return self::select('uid', 'status')->where('status', '<', '3')->whereNotin('uid', $current)->get();
    }

    public static function getRemovedOld()
    {
        return self::select('uid', 'status')->where('status', '3')->where('updated_at', '<', now()->modify('-24 hours'))->get();
    }

    public static function getList()
    {
        if (request()->list == "new") {
            return self::select('list.*')
                ->selectRaw('status.status as lineStatus, type.type as lineType')
                ->where('list.status', '0')->orderBy('uid')
                ->leftJoin('status', 'list.status', '=', 'status.id')
                ->leftJoin('type', 'list.type', '=', 'type.id')
                ->get();
        }
        if (request()->list == "open") {
            return self::select('list.*')->selectRaw('status.status as lineStatus, type.type as lineType')->leftJoin('status', 'list.status', '=', 'status.id')->leftJoin('type', 'list.type', '=', 'type.id')->where('list.status', '<', '3')->where('list.status', '>', '0')->orderBy('uid')->get();
        }
        if (request()->list == "closed") {
            return self::withTrashed()->select('list.*')->selectRaw('status.status as lineStatus, type.type as lineType')->leftJoin('status', 'list.status', '=', 'status.id')->leftJoin('type', 'list.type', '=', 'type.id')->where('list.status', '=', '3')->orderBy('uid')->get();
        }
        if (request()->list == "missing") {
            return self::select('list.*')->selectRaw('status.status as lineStatus, type.type as lineType')->where('list.type', '1')->where('list.status', '<', '4')->orderBy('uid')->leftJoin('status', 'list.status', '=', 'status.id')->leftJoin('type', 'list.type', '=', 'type.id')->get();
        }
        if (request()->list == "qa") {
            return self::select('list.*')->selectRaw('status.status as lineStatus, type.type as lineType')->leftJoin('status', 'list.status', '=', 'status.id')->leftJoin('type', 'list.type', '=', 'type.id')->where('list.type', '2')->where('list.status', '<', '4')->orderBy('uid')->get();
        }
        if (request()->list == "rma") {
            return self::select('list.*')->selectRaw('status.status as lineStatus, type.type as lineType')->leftJoin('status', 'list.status', '=', 'status.id')->leftJoin('type', 'list.type', '=', 'type.id')->where('list.type', '3')->where('list.status', '<', '4')->orderBy('uid')->get();
        }
        if (request()->list == "all") {
            return self::select('list.*')
                ->selectRaw('status.status as lineStatus, type.type as lineType, false as statusEdit, false as typeEdit, null as originalStatus, null as originalType')
                ->leftJoin('status', 'list.status', '=', 'status.id')
                ->leftJoin('type', 'list.type', '=', 'type.id')
                ->where('list.status', '<', '4')
                ->orderBy('uid')
                ->get();
        }
    }

    public static function checkQuantity($uid)
    {
        return self::select('uid', 'quantity_on_hand', 'status')
            ->where('uid', $uid)
            ->first();
    }

    public static function getLine()
    {
        return self::where('uid', request()->uid)->first();
    }

    public static function getDetail()
    {
        return self::where('uid', request()->uid)->first();
    }

    public static function getUpdateDetail()
    {
        return self::where('uid', request()->json()->all()['uid'])->first();
    }
}
