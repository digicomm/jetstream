<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RP_Receipts extends Model
{
    protected $connection = 'digismart';
    protected $table      = 'RP_Receipts';
    protected $fillable   = ['year', 'month', 'quarter', 'days', 'receipts', 'receipts_amount', 'receipts_lines', 'receipts_po', 'returns', 'returns_amount', 'returns_lines', 'returns_po', 'tallys', 'tallys_amount', 'tallys_lines', 'tallys_po', 'tallyreturns', 'tallyreturns_amount', 'tallyreturns_lines', 'tallyreturns_po', 'total', 'total_lines', 'total_po', 'tallytotal', 'tallytotal_lines', 'tallytotal_po'];

    public static function getReceiptStats()
    {
        return self::select('id', 'year', 'month', 'quarter', 'days', 'receipts', 'receipts_lines', 'receipts_po', 'receipts_amount', 'returns', 'returns_lines', 'returns_po', 'returns_amount', 'total', 'total_lines', 'total_po', 'total_amount')
            ->where('id', '<=', (date('Y')) . date('m'))
            ->where('id', '>=', '2020' . date('m'))
            ->orderBy('id', 'desc')
            ->get();
    }

    public static function getTallyStats()
    {
        return self::select('id', 'year', 'month', 'quarter', 'days', 'tallys', 'tallys_lines', 'tallys_po', 'tallys_amount', 'tallyreturns', 'tallyreturns_lines', 'tallyreturns_po', 'tallyreturns_amount', 'tallytotal', 'tallytotal_lines', 'tallytotal_po', 'tallytotal_amount')
            ->where('id', '<=', (date('Y')) . date('m'))
            ->where('id', '>=', '2020' . date('m'))
            ->orderBy('id', 'desc')
            ->get();
    }
}
