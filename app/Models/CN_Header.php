<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CN_Header extends Model
{
    public    $incrementing = false;
    protected $connection   = 'digismart';
    protected $table        = 'CN_Header';
    protected $fillable     = ['invoice_number', 'vendor_code', 'ship_date', 'master_tracking_number', 'cartons', 'status'];
    protected $primaryKey   = "invoice_number";
    protected $keyType      = "string";
    protected $casts        = ['test' => 'integer'];

    public static function getInvoicesByStatus($status = 'O', $sort_by = 'ship_date', $sort_desc = false)
    {
        return self::select('CN_Header.vendor_code', 'CN_Header.invoice_number', 'CN_Header.ship_date')
            ->selectRaw('(SUM(CN_Cartons.received)/COUNT(CN_Cartons.received)*100) as percent_cartons')
            ->selectRaw('(SUM(CN_Master.checked)/SUM(CN_Master.quantity)*100) as percent_checked')
            ->selectRaw('(SUM(CN_Master.received)/SUM(CN_Master.quantity)*100) as percent_received')
            ->selectRaw('null AS actions')
            ->join('CN_Master', 'CN_Header.invoice_number', '=', 'CN_Master.invoice_number')
            ->join('CN_Cartons', 'CN_Header.invoice_number', '=', 'CN_Cartons.invoice_number')
            ->groupBy('CN_Header.vendor_code', 'CN_Header.invoice_number', 'CN_Header.ship_date')
            ->where('CN_Header.status', $status)
            ->orderBy($sort_by, $sort_desc ? 'desc' : 'asc')
            ->orderBy('CN_Header.vendor_code')
            ->get();
    }

    public function verifyPoList()
    {
        return $this->hasMany(CN_POList::class, 'invoice_number', 'invoice_number');
    }

    public function verifyPackingList()
    {
        return $this->hasMany(CN_Packing::class, 'invoice_number', 'invoice_number');
    }

    public function verifyInvoiceList()
    {
        return $this->hasMany(CN_Invoice::class, 'invoice_number', 'invoice_number');
    }

    public function viewDetail()
    {
        return $this->hasMany(CN_Master::class, 'invoice_number', 'invoice_number')
            ->select('line', 'product_code', 'quantity', 'purchase_order', 'warehouse', 'checked', 'check_remaining', 'received', 'receive_remaining')
            ->orderBy('line');
    }

    public function viewReceiveParts()
    {
        return $this->hasMany(CN_Master::class, 'invoice_number', 'invoice_number')
            ->select('product_code')
            ->selectRaw('sum(quantity) as quantity, sum(checked) as checked, sum(check_remaining) as remaining')
            ->groupBy('product_code')
            ->orderBy('product_code');
    }

    public function listPartLines()
    {
        return $this->hasMany(CN_Master::class, 'invoice_number', 'invoice_number')
            ->select('id', 'product_code', 'quantity', 'checked', 'check_remaining')
            ->orderBy('product_code')
            ->orderBy('purchase_order');
    }

    public function listPurchaseOrders()
    {
        return $this->hasMany(CN_POList::class, 'invoice_number', 'invoice_number')
            ->select('purchase_order');
    }

    public function receiptDetail()
    {
        return $this->hasMany(CN_Master::class, 'invoice_number', 'invoice_number')
            ->select('product_code')
            ->selectRaw('SUM(quantity) as shipped, SUM(received) as received')
            ->groupBy('product_code')
            ->orderBy('product_code');
    }

    public function cartonList()
    {
        return $this->hasMany(CN_Cartons::class, 'invoice_number', 'invoice_number')
            ->select('id', 'invoice_number', 'carton', 'note', 'received');
    }

    public function openCartonList()
    {
        return $this->hasMany(CN_Cartons::class, 'invoice_number', 'invoice_number')
            ->select('id', 'invoice_number', 'carton', 'note', 'received')->where('received', '=', '0');
    }

    public function receivedCartonList()
    {
        return $this->hasMany(CN_Cartons::class, 'invoice_number', 'invoice_number')
            ->select('id', 'invoice_number', 'carton', 'note', 'received')
            ->where('received', '=', '1');
    }

    public function lastTestNumber()
    {
        return $this->hasMany(CN_TestHeader::class, 'invoice_number', 'invoice_number')
            ->select('invoice_number');
    }

    public function lastReceiptNumber()
    {
        return $this->hasMany(CN_ReceiptHeader::class, 'invoice_number', 'invoice_number')
            ->select('invoice_number');
    }

    public function getLabelList()
    {
        return $this->hasMany(CN_Invoice::class, 'invoice_number', 'invoice_number')
            ->select('product_code')
            ->selectRaw('SUM(quantity) as total')
            ->groupBy('product_code')
            ->orderBy('product_code', 'DESC');
    }


}
