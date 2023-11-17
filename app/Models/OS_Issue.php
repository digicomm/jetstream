<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OS_Issue extends Model
{
    use SoftDeletes;

    protected $connection = 'digismart';
    protected $table      = 'OS_Issue';
    protected $fillable   = ['reporter_id', 'handler_id', 'priority_id', 'status_id', 'vendor_id', 'purchase_order'];

    public function getList()
    {
        return self::select('OS_Issue.id', 'priority_id', 'vendor_id', 'purchase_order', 'status_id', 'OS_Issue.updated_at')
            ->selectRaw('LA_Users.name as handler')
            ->leftJoin('LA_Users', 'OS_Issue.handler_id', '=', 'LA_Users.id')
            ->where('status_id', '!=', '70')
            ->get();
    }

    public function getClosedList()
    {
        return self::select('OS_Issue.id', 'priority_id', 'vendor_id', 'purchase_order', 'status_id', 'OS_Issue.updated_at')
            ->selectRaw('LA_Users.name as handler')
            ->leftJoin('LA_Users', 'OS_Issue.handler_id', '=', 'LA_Users.id')
            ->where('status_id', '=', '70')
            ->get();
    }

    public function getStats()
    {
        return self::selectRaw('SUM(CASE WHEN status_id < 60 then 1 else 0 end) as open, SUM(CASE WHEN status_id = 60 then 1 else 0 end) as resolved, SUM(CASE WHEN status_id = 70 then 1 else 0 end) as closed')
            ->first();
    }

    public function getAdditional($id)
    {
        return self::select('LS_OverShortPriority.priority', 'LS_OverShortStatus.status')
            ->selectRaw('LA_Users.name as handler, CONCAT(AP_Vendor.VendorName, \' (\', AP_Vendor.VendorNo, \')\') as vendor')
            ->where('OS_Issue.id', '=', $id)
            ->leftJoin('LS_OverShortPriority', 'OS_Issue.priority_id', '=', 'LS_OverShortPriority.id')
            ->leftJoin('LS_OverShortStatus', 'OS_Issue.status_id', '=', 'LS_OverShortStatus.id')
            ->leftJoin('LA_Users', 'OS_Issue.handler_id', '=', 'LA_Users.id')
            ->leftJoin('AP_Vendor', 'OS_Issue.vendor_id', '=', 'AP_Vendor.VendorNo')
            ->first();
    }

    public function details()
    {
        return $this->hasMany(OS_Detail::class, 'issue_id');
    }

    public function files()
    {
        return $this->hasMany(OS_Files::class, 'issue_id')
            ->orderBy('filename')
            ->orderBy('extension')
            ->orderBy('created_at');
    }

    public function history()
    {
        return $this->hasMany(OS_History::class, 'issue_id')
            ->select('OS_History.created_at', 'LA_Users.username', 'OS_History.field_name', 'OS_History.old_value', 'OS_History.new_value')
            ->orderByDesc('OS_History.id')
            ->leftJoin('LA_Users', 'OS_History.user_id', '=', 'LA_Users.id');
    }
}

