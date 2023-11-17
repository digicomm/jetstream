<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BU_Lab extends Model
{
    use SoftDeletes;

    protected $connection = 'digismart';
    protected $table      = 'BU_Lab';
    protected $fillable   = ['special_instructions', 'sage_sales_order', 'order_number', 'customer_no', 'ship_to_city', 'promise_date', 'expected_date', 'po_line', 'product_code', 'quantity_ordered', 'quantity_shipped', 'quantity_open', 'quantity_building', 'quantity_available', 'quantity_allocated', 'status', 'assigned_to', 'priority', 'allocation'];
    protected $casts      = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    public static function getBuilds()
    {
        return self::get();
    }

    public static function getOpenBuilds()
    {
        return self::where('status', '<', '13')->get();
    }

    public static function getBuildAllocations()
    {
        return self::select('BU_Lab.id', 'BU_Lab.status', 'BU_Lab.quantity_open', 'BU_Lab.quantity_allocated')
            ->selectRaw('SP_OpenOrders.quantity_allocated as newQuantityAllocated')
            ->where('BU_Lab.status', '<', '12')
            ->leftJoin('SP_OpenOrders', function ($join) {
                $join->on('BU_Lab.sage_sales_order', '=', 'SP_OpenOrders.sage_sales_order');
                $join->on('BU_Lab.po_line', '=', 'SP_OpenOrders.po_line');
            })->get();
    }

    public static function getLine($uid)
    {
        return self::where('uid', $uid)->first();
    }

    public static function getArchivable()
    {
        return self::where('status', '13')
            ->where('updated_at', '<', date('Y-m-d', time() - 60 * 60 * 24))
            ->get();
    }

    public static function getList()
    {
        switch (request()->list) {
            case 'all':
                return self::select('BU_Lab.*')
                    ->selectRaw('BU_Priority.priority as priorityDesc, BU_Status.status as statusDesc, LA_Users.name')
                    ->leftJoin('BU_Priority', 'BU_Lab.priority', '=', 'BU_Priority.id')
                    ->leftJoin('BU_Status', 'BU_Lab.status', '=', 'BU_Status.id')
                    ->leftJoin('LA_Users', 'BU_Lab.assigned_to', '=', 'LA_Users.id')
                    ->get();
            case '2week':
                return self::select('BU_Lab.*')
                    ->selectRaw('BU_Priority.priority as priorityDesc, BU_Status.status as statusDesc, LA_Users.name')
                    ->whereBetween('BU_Lab.promise_date', [date('Y-m-d', strtotime('-14 days', time())), date('Y-m-d', strtotime('+14 days', time()))])
                    ->leftJoin('BU_Priority', 'BU_Lab.priority', '=', 'BU_Priority.id')
                    ->leftJoin('BU_Status', 'BU_Lab.status', '=', 'BU_Status.id')
                    ->leftJoin('LA_Users', 'BU_Lab.assigned_to', '=', 'LA_Users.id')
                    ->get();
            case 'new':
                return self::select('BU_Lab.*')
                    ->selectRaw('BU_Priority.priority as priorityDesc, BU_Status.status as statusDesc, LA_Users.name')
                    ->where('BU_Lab.status', '0')
                    ->leftJoin('BU_Priority', 'BU_Lab.priority', '=', 'BU_Priority.id')
                    ->leftJoin('BU_Status', 'BU_Lab.status', '=', 'BU_Status.id')
                    ->leftJoin('LA_Users', 'BU_Lab.assigned_to', '=', 'LA_Users.id')
                    ->get();
            case 'reviewed':
                return self::select('BU_Lab.*')->selectRaw('BU_Priority.priority as priorityDesc, BU_Status.status as statusDesc, LA_Users.name')->where('BU_Lab.status', '1')->leftJoin('BU_Priority', 'BU_Lab.priority', '=', 'BU_Priority.id')->leftJoin('BU_Status', 'BU_Lab.status', '=', 'BU_Status.id')->leftJoin('LA_Users', 'BU_Lab.assigned_to', '=', 'LA_Users.id')->get();
            case 'unable':
                return self::select('BU_Lab.*')->selectRaw('BU_Priority.priority as priorityDesc, BU_Status.status as statusDesc, LA_Users.name')->where('BU_Lab.status', '2')->leftJoin('BU_Priority', 'BU_Lab.priority', '=', 'BU_Priority.id')->leftJoin('BU_Status', 'BU_Lab.status', '=', 'BU_Status.id')->leftJoin('LA_Users', 'BU_Lab.assigned_to', '=', 'LA_Users.id')->get();
            case 'hold':
                return self::select('BU_Lab.*')->selectRaw('BU_Priority.priority as priorityDesc, BU_Status.status as statusDesc, LA_Users.name')->where('BU_Lab.status', '3')->leftJoin('BU_Priority', 'BU_Lab.priority', '=', 'BU_Priority.id')->leftJoin('BU_Status', 'BU_Lab.status', '=', 'BU_Status.id')->leftJoin('LA_Users', 'BU_Lab.assigned_to', '=', 'LA_Users.id')->get();
            case 'lab':
                return self::select('BU_Lab.*')->selectRaw('BU_Priority.priority as priorityDesc, BU_Status.status as statusDesc, LA_Users.name')->where('BU_Lab.priority', '>=', '5')->leftJoin('BU_Priority', 'BU_Lab.priority', '=', 'BU_Priority.id')->leftJoin('BU_Status', 'BU_Lab.status', '=', 'BU_Status.id')->leftJoin('LA_Users', 'BU_Lab.assigned_to', '=', 'LA_Users.id')->get();
            case 'builds':
                return self::select('BU_Lab.*')->selectRaw('BU_Priority.priority as priorityDesc, BU_Status.status as statusDesc, LA_Users.name')->where('BU_Lab.status', '>=', '7')->where('BU_Lab.status', '<=', '9')->leftJoin('BU_Priority', 'BU_Lab.priority', '=', 'BU_Priority.id')->leftJoin('BU_Status', 'BU_Lab.status', '=', 'BU_Status.id')->leftJoin('LA_Users', 'BU_Lab.assigned_to', '=', 'LA_Users.id')->get();
            case 'shipped':
                return self::select('BU_Lab.*')->selectRaw('BU_Priority.priority as priorityDesc, BU_Status.status as statusDesc, LA_Users.name')->where('BU_Lab.status', '=', '12')->leftJoin('BU_Priority', 'BU_Lab.priority', '=', 'BU_Priority.id')->leftJoin('BU_Status', 'BU_Lab.status', '=', 'BU_Status.id')->leftJoin('LA_Users', 'BU_Lab.assigned_to', '=', 'LA_Users.id')->get();
            case 'assigned':
                return self::select('BU_Lab.*')
                    ->selectRaw('BU_Priority.priority as priorityDesc, BU_Status.status as statusDesc, LA_Users.name')
                    ->where('BU_Lab.assigned_to', '=', Auth()->user()->id)
                    ->leftJoin('BU_Priority', 'BU_Lab.priority', '=', 'BU_Priority.id')
                    ->leftJoin('BU_Status', 'BU_Lab.status', '=', 'BU_Status.id')
                    ->leftJoin('LA_Users', 'BU_Lab.assigned_to', '=', 'LA_Users.id')
                    ->get();
        }
    }

    public static function getDetail($id)
    {
        return self::where('id', $id)->first();
    }

    public static function getDetailUpdate($id)
    {
        return self::where('id', $id)->first();
    }

    public static function getMultiDetailUpdate($id)
    {
        return self::where('id', $id)->first();
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
