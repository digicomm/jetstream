<?php

namespace App\Models;

use App\Digismart;
use DateTime;
use Illuminate\Database\Eloquent\Model;
use stdClass;

class RP_Shipping extends Model
{
    protected $connection = 'digismart';
    protected $table      = 'RP_Shipping';
    protected $fillable   = ['year', 'month', 'quarter', 'dats', 'errors', 'lines_shipped', 'invoices', 'shipments'];
    protected $casts      = [
        'freight' => 'double',
        'invoice_error_rate' => 'double',
        'invoices_day' => 'double',
        'invoices_shipment' => 'double',
        'lines_day' => 'double',
        'lines_invoice' => 'double',
        'lines_shipment' => 'double',
        'non_taxable' => 'double',
        'taxable' => 'double',
        'sales_tax' => 'double',
        'shipment_accuracy' => 'double',
        'shipment_error_rate' => 'double',
        'shipments_day' => 'double',
        'total_invoiced' => 'double',
    ];

    public static function getMonthStats()
    {
        return self::select('*')->selectRaw('false as errors_edit')
            ->where('id', '<=', (date('Y')) . date('m'))
            ->orderBy('id', 'desc')
            ->get();
    }

    public static function getQuarterStats()
    {
        return self::selectRaw('year, quarter, SUM(`days`) as `days`, SUM(`errors`) as `errors`, SUM(`lines_shipped`) as `lines_shipped`, (SUM(`lines_shipped`)/SUM(`days`)) as `lines_day`, SUM(`invoices`) as `invoices`, (SUM(`invoices`)/SUM(`days`)) as `invoices_day`, SUM(`shipments`) as `shipments`, (SUM(`shipments`)/SUM(`days`)) as `shipments_day`, (SUM(`errors`)/SUM(`invoices`)*100) as `invoice_error_rate`, (SUM(`errors`)/SUM(`shipments`)*100) as `shipment_error_rate`, ((1-(SUM(`errors`)/SUM(`shipments`)))*100) as `shipment_accuracy`, (SUM(`invoices`)/SUM(`shipments`)) as `invoices_shipment`, (SUM(`lines_shipped`)/SUM(`invoices`)) as `lines_invoice`, (SUM(`lines_shipped`)/SUM(`shipments`)) as `lines_shipment`')
            ->where('id', '<=', (date('Y')) . date('m'))
            ->groupBy('year', 'quarter')
            ->orderBy('year', 'desc')
            ->orderBy('quarter', 'desc')->get();
    }


    public static function getShippingCharts()
    {
        $days = Digismart::getWorkDaysMonth();
        $labels = array();

        $borderColor[3] = 'rgba(255, 232, 2, .7)';
        $borderColor[2] = 'rgba(40, 218, 198, .7)';
        $borderColor[1] = 'rgba(41, 154, 255, .7)';
        $borderColor[0] = 'rgba(0, 0, 0, .3)';
        $backgroundColor[3] = 'rgba(255, 232, 2, .7)';
        $backgroundColor[2] = 'rgba(40, 218, 198, .7)';
        $backgroundColor[1] = 'rgba(41, 154, 255, .7)';
        $backgroundColor[0] = 'rgba(0, 0, 0, .3)';

        // Month Year Labels
        $month = self::select('year', 'month')
            ->where('id', '>=', (date('Y') - 1) . date('m'))
            ->where('id', '<=', (date('Y')) . date('m'))
            ->orderBy('year')
            ->orderBy('month')
            ->get();
        foreach ($month as $rolling) {
            $dateObj = DateTime::createFromFormat('!m', $rolling->month);
            $monthName = $dateObj->format('M');
            $labels[] = $monthName . ' ' . $rolling->year;
        }

        // Current Year
        $lines = self::select('year', 'month', 'lines_shipped', 'lines_day', 'shipments', 'shipments_day', 'invoices', 'invoices_day', 'total_invoiced')
            ->where('id', '>=', (date('Y') - 1) . date('m'))
            ->where('id', '<=', (date('Y')) . date('m'))
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        $lines[12]->lines_day = $days !== 0 ? $lines[12]->lines_shipped / $days : 0;
        $lines[12]->shipments_day = $days !== 0 ? $lines[12]->shipments / $days : 0;
        $lines[12]->invoices_day = $days !== 0 ? $lines[12]->invoices / $days : 0;

        foreach ($lines as $linea) {
            $line_data[] = $linea->lines_day;
            $shipment_data[] = $linea->shipments_day;
            $invoice_data[] = $linea->invoices_day;
            $invoiced_data[] = $linea->total_invoiced;
        }
        $linesO = new stdClass();
        $linesO->data = $line_data;
        $linesO->backgroundColor = $backgroundColor[3];
        $linesO->fill = true;

        $invoicesO = new stdClass();
        $invoicesO->data = $invoice_data;
        $invoicesO->backgroundColor = $backgroundColor[2];
        $invoicesO->fill = true;

        $shipmentsO = new stdClass();
        $shipmentsO->data = $shipment_data;
        $shipmentsO->backgroundColor = $backgroundColor[1];
        $shipmentsO->fill = true;

        $invoicedO = new stdClass();
        $invoicedO->data = $invoiced_data;
        $invoicedO->backgroundColor = $backgroundColor[2];
        $invoicedO->yAxisId = 'invoiced';
        $invoicedO->fill = true;

        for ($x = 3; $x >= 0; $x--) {
            $lines = self::select('year', 'month', 'lines_shipped', 'lines_day', 'shipments', 'shipments_day', 'invoices', 'invoices_day', 'total_invoiced')
                ->where('id', '>=', (date('Y') - $x) . '01')
                ->where('id', '<=', (date('Y') - $x) . '12')
                ->orderBy('year')
                ->orderBy('month')
                ->get();
            $line[(date('Y') - $x)] = $lines;
            if ($x === 0) {
                $line[(date('Y') - $x)][intval(date('m', time())) - 1]->lines_day = $days !== 0 ? $line[(date('Y') - $x)][intval(date('m', time())) - 1]->lines_shipped / $days : 0;
                $line[(date('Y') - $x)][intval(date('m', time())) - 1]->shipments_day = $days !== 0 ? $line[(date('Y') - $x)][intval(date('m', time())) - 1]->shipments / $days : 0;
                $line[(date('Y') - $x)][intval(date('m', time())) - 1]->invoices_day = $days !== 0 ? $line[(date('Y') - $x)][intval(date('m', time())) - 1]->invoices / $days : 0;
            }
        }

        foreach ($line as $year => $data) {
            foreach ($data as $month) {
                $lineyoy_data[$year][] = $month->lines_day;
                $shipmentyoy_data[$year][] = $month->shipments_day;
                $invoiceyoy_data[$year][] = $month->invoices_day;
                $invoicedyoy_data[$year][] = $month->total_invoiced;
            }
            $linesyoyO[$year] = new stdClass();
            $linesyoyO[$year]->data = $lineyoy_data[$year];
            $linesyoyO[$year]->label = $year;
            $linesyoyO[$year]->backgroundColor = $backgroundColor[date('Y', time()) - $year];
            $linesyoyO[$year]->borderColor = $borderColor[date('Y', time()) - $year];
            $linesyoyO[$year]->fill = (date('Y', time()) - $year) === 0 ? true : false;

            $invoicesyoyO[$year] = new stdClass();
            $invoicesyoyO[$year]->data = $invoiceyoy_data[$year];
            $invoicesyoyO[$year]->label = $year;
            $invoicesyoyO[$year]->backgroundColor = $backgroundColor[date('Y', time()) - $year];
            $invoicesyoyO[$year]->borderColor = $borderColor[date('Y', time()) - $year];
            $invoicesyoyO[$year]->fill = (date('Y', time()) - $year) === 0 ? true : false;

            $shipmentsyoyO[$year] = new stdClass();
            $shipmentsyoyO[$year]->data = $shipmentyoy_data[$year];
            $shipmentsyoyO[$year]->label = $year;
            $shipmentsyoyO[$year]->backgroundColor = $backgroundColor[date('Y', time()) - $year];
            $shipmentsyoyO[$year]->borderColor = $borderColor[date('Y', time()) - $year];
            $shipmentsyoyO[$year]->fill = (date('Y', time()) - $year) === 0 ? true : false;

            $invoicedyoyO[$year] = new stdClass();
            $invoicedyoyO[$year]->data = $invoicedyoy_data[$year];
            $invoicedyoyO[$year]->label = $year;
            $invoicedyoyO[$year]->backgroundColor = $backgroundColor[date('Y', time()) - $year];
            $invoicedyoyO[$year]->borderColor = $borderColor[date('Y', time()) - $year];
            $invoicedyoyO[$year]->fill = (date('Y', time()) - $year) === 0 ? true : false;
        }

        return array(
            'lines' => array('labels' => $labels, 'datasets' => array($linesO)),

            'invoices' => array('labels' => $labels, 'datasets' => array($invoicesO)),
            'shipments' => array('labels' => $labels, 'datasets' => array($shipmentsO)),
            'invoiced' => array('labels' => $labels, 'datasets' => array($invoicedO)),
            'linesyoy' => array('labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'], 'datasets' => array_values($linesyoyO)),
            'invoicesyoy' => array('labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'], 'datasets' => array_values($invoicesyoyO)),
            'shipmentsyoy' => array('labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'], 'datasets' => array_values($shipmentsyoyO)),
            'invoicedyoy' => array('labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'], 'datasets' => array_values($invoicedyoyO)),

        );
    }
}
