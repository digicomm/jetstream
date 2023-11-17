<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CC_Header extends Model
{
    protected $connection = 'digismart';
    protected $table      = 'CC_Header';
    protected $fillable   = ['status', 'created_by', 'updated_by', 'closed_by', 'count_date', 'closed', 'year'];
    protected $casts      = [
        'created_at' => 'datetime:Y-m-d',
        'closed_at' => 'datetime:Y-m-d',
    ];

    public static function getClosedCountProducts($year)
    {
        return self::select('CC_Header.id', 'CC_Header.closed_at', 'CC_CountProducts.product_code')
            ->where('status', '=', '1')
            ->where('year', '=', $year)
            ->crossJoin('CC_CountProducts', 'CC_Header.id', '=', 'CC_CountProducts.header_id')
            ->orderBy('CC_Header.closed_at')
            ->orderBy('CC_CountProducts.product_code')
            ->get();
    }

    public static function getOpenCounts()
    {
        return self::select('CC_Header.id', 'CC_Header.created_by', 'CC_Header.created_at')
            ->selectRaw('GROUP_CONCAT(CC_CountProducts.product_code ORDER BY CC_CountProducts.product_code ASC SEPARATOR \', \') as products')
            ->where('status', '0')
            ->leftJoin('CC_CountProducts', 'CC_Header.id', '=', 'CC_CountProducts.header_id')
            ->orderByDesc('CC_Header.id')
            ->groupBy('CC_Header.id', 'CC_Header.created_by', 'CC_Header.created_at')
            ->get();
    }

    public static function getClosedCounts()
    {
        return self::select('CC_Header.id', 'CC_Header.closed_by', 'CC_Header.closed_at')
            ->selectRaw('GROUP_CONCAT(CC_CountProducts.product_code ORDER BY CC_CountProducts.product_code ASC SEPARATOR \', \') as products')
            ->where('CC_Header.status', '1')
            ->leftJoin('CC_CountProducts', 'CC_Header.id', '=', 'CC_CountProducts.header_id')
            ->orderByDesc('CC_Header.id')
            ->groupBy('CC_Header.id', 'CC_Header.closed_by', 'CC_Header.closed_at')
            ->limit(50)
            ->get();
    }

    public static function getYearList()
    {
        return self::selectRaw('YEAR(closed_at) as text, YEAR(closed_at) as value')
            ->where('status', '1')
            ->orderBy('text')
            ->distinct()
            ->get();
    }

    public function countProducts()
    {
        return $this->hasMany(CC_CountProducts::class, 'header_id')
            ->orderBy('product_code', 'ASC');
    }

    public function detail()
    {
        return $this->hasMany(CC_Detail::class, 'header_id')
            ->orderBy('product_code')
            ->orderBy('bin_location')
            ->orderBy('warehouse_code');
    }
}
