<?php

namespace App\Http\Controllers;

use App\Models\SP_BinLocation;
use App\Models\SP_InventoryOnHand;
use Illuminate\Http\Request;
use Inertia\Inertia;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Worksheet\Table;
use PhpOffice\PhpSpreadsheet\Worksheet\Table\TableStyle;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class IN_ViewByController extends Controller
{
    public function index()
    {
        return Inertia::render('Inventory/View/ViewByBin');
    }
    public function show(Request $request, $type)
    {
        return match ($type) {
            'bin' => Inertia::render('Inventory/View/ViewByBin'),
            'aisle' => Inertia::render('Inventory/View/ViewByAisle'),
            'product' => Inertia::render('Inventory/View/ViewByProduct'),
            'empty' => Inertia::render('Inventory/View/ViewByEmpty'),
            default => false,
        };
    }
    public function update(Request $request, $type)
    {
        return match ($type) {
            'bin' => response()->json(array("data" => SP_InventoryOnHand::getInventoryByBin($request->search))),
            'aisle' => response()->json(array("data" => SP_InventoryOnHand::getInventoryByAisle($request->search))),
            'product' => response()->json(array("data" => SP_InventoryOnHand::getInventoryByProduct($request->search))),
            'empty' => response()->json(array("data" => SP_BinLocation::getEmptyBins())),
            default => false,
        };
    }

    public function create(Request $request)
    {

        if(!$request->bin) abort(500, 'No Bin Selected');
        $records = SP_InventoryOnHand::getInventoryByBin($request->search);
        try {
            if(count($records) === 0) abort(500, 'No Records for Bin Location');
        } catch(\TypeError $e) {
            abort(500, 'Type Error');
        }

        $spreadsheet = new Spreadsheet();
        $activeSheet = $spreadsheet->getActiveSheet();
        $spreadsheet->getProperties()->setCreator('Raul Wager')->setLastModifiedBy('Raul Wager');
        $spreadsheet->getDefaultStyle()->getFont()->setName('Arial');
        $spreadsheet->setActiveSheetIndex(0);

        $activeSheet->mergeCells('A1:J1');
        $activeSheet->mergeCells('A2:J2');
        $activeSheet->mergeCells('A3:J3');
        $activeSheet->setCellValue('A1', 'DIGICOMM INTERNATIONAL, INC');
        $activeSheet->setCellValue('A2', 'INVENTORY BY BIN');
        $activeSheet->setCellValue('A3', strtoupper($request->bin));
        $activeSheet->getStyle('A1:J3')->getFont()->setBold(true);

        $activeSheet->getStyle('A1:J3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);



        $activeSheet->setCellValue('A4', 'Bin Location');
        $activeSheet->getStyle('A4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $activeSheet->setCellValue('B4', 'Product Code');
        $activeSheet->getStyle('B4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $activeSheet->setCellValue('C4', 'Manufacturer');
        $activeSheet->getStyle('C4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $activeSheet->setCellValue('D4', 'WHS');
        $activeSheet->getStyle('D4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $activeSheet->setCellValue('E4', 'On Hand');
        $activeSheet->getStyle('E4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $activeSheet->setCellValue('F4', 'Allocated');
        $activeSheet->getStyle('F4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $activeSheet->setCellValue('G4', 'Available');
        $activeSheet->getStyle('G4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $activeSheet->setCellValue('H4', 'FIFO');
        $activeSheet->getStyle('H4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $activeSheet->setCellValue('I4', 'Last Ship');
        $activeSheet->getStyle('I4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $activeSheet->setCellValue('J4', 'Last Adj');
        $activeSheet->getStyle('J4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);






        for($i = 0; $i < count($records); $i++) {
            $activeSheet->getCell('A' . ($i + 5))->setValueExplicit($records[$i]->bin_location);
            $activeSheet->getCell('B' . ($i + 5))->setValueExplicit($records[$i]->product_code);
            $activeSheet->setCellValue('C' . ($i + 5), $records[$i]->manufacturer);
            $activeSheet->setCellValue('D' . ($i + 5), $records[$i]->warehouse_code);
            $activeSheet->setCellValue('E' . ($i + 5), $records[$i]->quantity_on_hand);
            $activeSheet->setCellValue('F' . ($i + 5), $records[$i]->quantity_allocated);
            $activeSheet->setCellValue('G' . ($i + 5), $records[$i]->quantity_available);
            $activeSheet->setCellValue('H' . ($i + 5), $records[$i]->fifo_lifo);
            $activeSheet->setCellValue('I' . ($i + 5), $records[$i]->last_shipment);
            $activeSheet->setCellValue('J' . ($i + 5), $records[$i]->last_adjustment);

            $activeSheet->getStyle('A' . ($i + 5))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $activeSheet->getStyle('B' . ($i + 5))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $activeSheet->getStyle('C' . ($i + 5))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $activeSheet->getStyle('D' . ($i + 5))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $activeSheet->getStyle('E' . ($i + 5))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $activeSheet->getStyle('F' . ($i + 5))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $activeSheet->getStyle('G' . ($i + 5))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $activeSheet->getStyle('H' . ($i + 5))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $activeSheet->getStyle('I' . ($i + 5))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $activeSheet->getStyle('J' . ($i + 5))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }



        $activeSheet->getColumnDimension('A')->setAutoSize(true);
        $activeSheet->getColumnDimension('B')->setAutoSize(true);
        $activeSheet->getColumnDimension('C')->setAutoSize(true);
        $activeSheet->getColumnDimension('D')->setAutoSize(true);
        $activeSheet->getColumnDimension('E')->setAutoSize(true);
        $activeSheet->getColumnDimension('F')->setAutoSize(true);
        $activeSheet->getColumnDimension('G')->setAutoSize(true);
        $activeSheet->getColumnDimension('H')->setAutoSize(true);
        $activeSheet->getColumnDimension('I')->setAutoSize(true);
        $activeSheet->getColumnDimension('J')->setAutoSize(true);


        $table = new Table('A4:J' . count($records) + 4, 'InventoryBin');
        $tableStyle = new TableStyle();
        $tableStyle->setTheme(TableStyle::TABLE_STYLE_MEDIUM2);
        $tableStyle->setShowRowStripes(true);
        $tableStyle->setShowColumnStripes(false);
        $tableStyle->setShowFirstColumn(false);
        $tableStyle->setShowLastColumn(false);
        $table->setStyle($tableStyle);

        $activeSheet->addTable($table);


        $activeSheet->freezePane('A5');
        $activeSheet->setTitle('Inventory By Bin ' & $request->bin);
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
        header('Content-Disposition: attachment;filename="inventorybybin.xlsx"');
        header('Cache-Control: max-age=0');
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }
}
