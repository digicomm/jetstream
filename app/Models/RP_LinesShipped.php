<?php

namespace App\Models;

use App\Digismart;
use DateTime;
use Illuminate\Database\Eloquent\Model;

class RP_LinesShipped extends Model
{
    protected $connection   = 'digismart';
    protected $table        = 'RP_LinesShipped';
    protected $fillable     = ['lines_shipped'];
    protected $casts        = [
        'lines_shipped' => 'double'
    ];
    protected $keyType      = 'string';
    protected $primaryKey   = 'date';
    public    $incrementing = false;

    public static function getLinesDay()
    {
        $endDate = new DateTime(date('Y-m-d'));
        $endDate->modify('-6 months');
        $dates = array_reverse(Digismart::getDatesFromRange($endDate->format('Y-m-d'), date('Y-m-d')));
        return self::select('date', 'lines_shipped')
            ->selectRaw('date as summary, date as detail')
            ->whereIn('date', $dates)
            ->where('lines_shipped', '>', '0')
            ->orderBy('date', 'desc')
            ->get();
    }

    public static function getLinesWeek()
    {
        $years = range(date('Y'), date('Y') - 2);
        foreach ($years as $year) {
            for ($x = 52; $x >= 1; $x--) {
                $divideBy = 5;
                $dates = Digismart::getStartAndEndDate($year, $x);
                $weekLines = RP_LinesShipped::getLinesQuery($dates['start'], $dates['end']);

                foreach (Digismart::getHolidays($year) as $holiday) {
                    if ($holiday >= $dates['start'] && $holiday <= $dates['end']) {
                        $divideBy = $divideBy - 1;
                    }
                }
                if ($weekLines > 0) {
                    $data[] = array("week" => $year . "-" . str_pad($x, 2, "0", STR_PAD_LEFT), "start" => $dates['start'], "end" => $dates['end'], "work_days" => $divideBy, "lines_shipped" => $weekLines, "average" => number_format(($weekLines / $divideBy), 2));
                }
            }
        }
        return $data;
    }

    public static function getLinesMonth()
    {
        $years = range(date('Y'), date('Y') - 2);
        $x = 0;
        foreach ($years as $year) {
            $months = range(12, 1);
            foreach ($months as $month) {
                $startDate = date('Y-m-d', strtotime($year . "-" . str_pad($month, 2, "0", STR_PAD_LEFT) . "-" . "01"));
                if (date('Y-m', strtotime($year . '-' . $month . '-01')) === date('Y-m', time())) {
                    $endDate = date('Y-m-d', strtotime($year . "-" . str_pad($month, 2, "0", STR_PAD_LEFT) . "-" . (date('j') - 1)));
                } else {
                    $endDate = date('Y-m-d', strtotime($year . "-" . str_pad($month, 2, "0", STR_PAD_LEFT) . "-" . cal_days_in_month(CAL_GREGORIAN, $month, $year)));
                }
                $subtractDays = 0;
                if ($month == "5" || $month == "9" || $month == "11") {
                    $subtractDays = 1;
                } else if ($month == "1") {
                    if (date('N', mktime(0, 0, 0, 1, 1, $year)) < 6) {
                        $SubtractDays = 1;
                    }
                } else if ($month == "7") {
                    if (date('N', mktime(0, 0, 0, 7, 4, $year)) < 6) {
                        $subtractDays = 1;
                    }
                } else if ($month == "12") {
                    if (date('N', mktime(0, 0, 0, 12, 25, $year)) < 6) {
                        $subtractDays = 1;
                    }
                }
                $workDays = (Digismart::getWorkingDays($startDate, $endDate) - $subtractDays);

                $linesShipped = RP_LinesShipped::getLinesQuery($startDate, $endDate);

                if ($linesShipped > 0) {
                    $data[] = array("id" => $x, "date" => str_pad($month, 2, "0", STR_PAD_LEFT) . "/" . $year, "work_days" => $workDays, "lines_shipped" => $linesShipped, "average" => number_format(($linesShipped / $workDays), 2));
                }
                $x = $x + 1;
            }
        }
        return $data;
    }

    public static function getLinesQuery($start, $end)
    {
        return self::where('date', '>=', $start)->where('date', '<=', $end)->sum('lines_shipped');
    }
}
