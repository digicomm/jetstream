<?php

namespace App\Http\Controllers;

use App\Models\DS_VarianceReport;
use Illuminate\Support\Facades\Artisan;
use Inertia\Inertia;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Worksheet\Table;
use PhpOffice\PhpSpreadsheet\Worksheet\Table\TableStyle;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class IN_VarianceReportController extends Controller
{
    public function index()
    {
        return Inertia::render('Inventory/VarianceReport');
    }
    public function create()
    {
        $spreadsheet = new Spreadsheet();
        $activeSheet = $spreadsheet->getActiveSheet();
        $spreadsheet->getProperties()->setCreator('Raul Wager')->setLastModifiedBy('Raul Wager');
        $spreadsheet->getDefaultStyle()->getFont()->setName('Arial');
        $spreadsheet->setActiveSheetIndex(0);

        $activeSheet->mergeCells('A1:F1');
        $activeSheet->mergeCells('A2:F2');
        $activeSheet->mergeCells('A3:F3');
        $activeSheet->setCellValue('A1', 'DIGICOMM INTERNATIONAL, INC');
        $activeSheet->setCellValue('A2', 'INVENTORY VARIANCE REPORT');
        $activeSheet->setCellValue('A3', '');
        $activeSheet->getStyle('A1:A3')->getFont()->setBold(true);

        $activeSheet->getStyle('A1:A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        

        $activeSheet->setCellValue('A4', 'Product Code');
        $activeSheet->getStyle('A4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $activeSheet->setCellValue('B4', 'Warehouse');
        $activeSheet->getStyle('B4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $activeSheet->setCellValue('C4', 'Sage On Hand');
        $activeSheet->getStyle('C4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $activeSheet->setCellValue('D4', 'WMS On Hand');
        $activeSheet->getStyle('D4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $activeSheet->setCellValue('E4', 'Variance');
        $activeSheet->getStyle('E4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $activeSheet->setCellValue('F4', 'Notes');
        $activeSheet->getStyle('F4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

        $records = DS_VarianceReport::getVarianceReport();

       for($i = 0; $i < count($records); $i++) {
           $activeSheet->getCell('A' . ($i + 5))->setValueExplicit($records[$i]->product_code);
           $activeSheet->getCell('B' . ($i + 5))->setValueExplicit($records[$i]->warehouse_code);
           $activeSheet->setCellValue('C' . ($i + 5), $records[$i]->sage);
           $activeSheet->getStyle('C' . ($i + 5))->getNumberFormat()->setFormatCode('#,##0;[Red]-#,##0');
           $activeSheet->setCellValue('D' . ($i + 5), $records[$i]->wms);
           $activeSheet->getStyle('D' . ($i + 5))->getNumberFormat()->setFormatCode('#,##0;[Red]-#,##0');
           $activeSheet->setCellValue('E' . ($i + 5), $records[$i]->variance);
           $activeSheet->getStyle('E' . ($i + 5))->getNumberFormat()->setFormatCode('#,##0;[Red]-#,##0');
           $activeSheet->setCellValue('F' . ($i + 5), '');

           $activeSheet->getStyle('A' . ($i + 5))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
           $activeSheet->getStyle('B' . ($i + 5))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
           $activeSheet->getStyle('C' . ($i + 5))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
           $activeSheet->getStyle('D' . ($i + 5))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
           $activeSheet->getStyle('E' . ($i + 5))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
       }



        $activeSheet->getColumnDimension('A')->setWidth(40);
        $activeSheet->getColumnDimension('B')->setWidth(15);
        $activeSheet->getColumnDimension('C')->setAutoSize(true);
        $activeSheet->getColumnDimension('D')->setAutoSize(true);
        $activeSheet->getColumnDimension('E')->setAutoSize(true);
        $activeSheet->getColumnDimension('F')->setWidth(40);


        $table = new Table('A4:F' . count($records) + 4, 'Variances');
        $tableStyle = new TableStyle();
        $tableStyle->setTheme(TableStyle::TABLE_STYLE_MEDIUM2);
        $tableStyle->setShowRowStripes(true);
        $tableStyle->setShowColumnStripes(false);
        $tableStyle->setShowFirstColumn(false);
        $tableStyle->setShowLastColumn(false);
        $table->setStyle($tableStyle);

        $activeSheet->addTable($table);


        $activeSheet->freezePane('A5');
        $activeSheet->setTitle('Variance Report');
        $activeSheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
        $activeSheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_LETTER);
        $activeSheet->getPageMargins()->setTop(0.25);
        $activeSheet->getPageMargins()->setRight(0.25);
        $activeSheet->getPageMargins()->setLeft(0.25);
        $activeSheet->getPageMargins()->setBottom(0.5);
        $activeSheet->getPageSetup()->setHorizontalCentered(true);
        $activeSheet->getPageSetup()->setFitToWidth(1);
        $activeSheet->getPageSetup()->setFitToHeight(0);
        $activeSheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 5);
        $activeSheet->getHeaderFooter()->setOddFooter('&CPage &P of &N&R&D &T');
        $activeSheet->getHeaderFooter()->setEvenFooter('&CPage &P of &N&R&D &T');
        $activeSheet->getPageSetup()->setPrintArea('A:F');





        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="variancereport.xlsx"');
        header('Cache-Control: max-age=0');
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');



    }
    public function show()
    {
        return response()->json(array('data' => DS_VarianceReport::getVarianceReport()));
    }
    public function store()
    {
        Artisan::call('app:inventory-variance-report');
        return response()->json(array('data' => DS_VarianceReport::getVarianceReport()));
    }
}
