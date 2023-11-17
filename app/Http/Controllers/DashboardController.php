<?php

namespace App\Http\Controllers;

use App\Models\MAS_SO_InvoiceHeader;
use App\Models\SP_OpenOrders;
use App\Models\SP_PostedShipments;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('Dashboard', [
            'data' => in_array('data', explode(',', $request->header('X-Inertia-Partial-Data')))
                ? array(
                    "openOrders" => array(
                        SP_OpenOrders::getDashboardPast(),
                        SP_OpenOrders::getDashboardTwoWeeksPast(),
                        SP_OpenOrders::getDashboardTwoWeeksFuture(),
                        SP_OpenOrders::getDashboardFuture(),
                    ),
                    "ordersAllocated" => array(
                        SP_OpenOrders::getDashboardComplete(),
                        SP_OpenOrders::getDashboardPartial(),
                        SP_OpenOrders::getDashboardUnallocated()
                    ),
                    "ordersPrinted" => array(
                        SP_OpenOrders::getDashboardPrinted(),
                        SP_OpenOrders::getDashboardUnprinted()
                    ),
                    "postedShipments" => SP_PostedShipments::getPostedCount(),
                    "printed" => intval(SP_OpenOrders::getDollarsPrinted()),
                    "unprinted" => intval(SP_OpenOrders::getDollarsUnprinted()),
                    "twoWeeksFuture" => intval(SP_OpenOrders::getDollarsTwoWeeksFuture()),
                    "twoWeeksPast" => intval(SP_OpenOrders::getDollarsTwoWeeksPast()),
                    "future" => intval(SP_OpenOrders::getDollarsFuture()),
                    "past" => intval(SP_OpenOrders::getDollarsPast()),
                    "invoiced" => floatval(MAS_SO_InvoiceHeader::getInvoiceTotal())
                )
                : array(
                    "openOrders" => array(
                        1, 1, 1, 1
                    ),
                    "ordersAllocated" => array(
                        1, 1, 1
                    ),
                    "ordersPrinted" => array(
                        1, 1
                    ),
                    "postedShipments" => array(
                        'posted' => 0,
                        'cancelled' => 0,
                        'total' => 0
                    ),
                    "printed" => 0,
                    "unprinted" => 0,
                    "twoWeeksFuture" => 0,
                    "twoWeeksPast" => 0,
                    "future" => 0,
                    "past" => 0,
                    "invoiced" => 0
                )

        ]);
    }
    public function show()
    {
        return  response()->json(array(
            "openOrders" => array(
                SP_OpenOrders::getDashboardPast(),
                SP_OpenOrders::getDashboardTwoWeeksPast(),
                SP_OpenOrders::getDashboardTwoWeeksFuture(),
                SP_OpenOrders::getDashboardFuture(),
            ),
            "ordersAllocated" => array(
                SP_OpenOrders::getDashboardComplete(),
                SP_OpenOrders::getDashboardPartial(),
                SP_OpenOrders::getDashboardUnallocated()
            ),
            "ordersPrinted" => array(
                SP_OpenOrders::getDashboardPrinted(),
                SP_OpenOrders::getDashboardUnprinted()
            ),
            "postedShipments" => SP_PostedShipments::getPostedCount(),
            "printed" => intval(SP_OpenOrders::getDollarsPrinted()),
            "unprinted" => intval(SP_OpenOrders::getDollarsUnprinted()),
            "twoWeeksFuture" => intval(SP_OpenOrders::getDollarsTwoWeeksFuture()),
            "twoWeeksPast" => intval(SP_OpenOrders::getDollarsTwoWeeksPast()),
            "future" => intval(SP_OpenOrders::getDollarsFuture()),
            "past" => intval(SP_OpenOrders::getDollarsPast()),
            "invoiced" => floatval(MAS_SO_InvoiceHeader::getInvoiceTotal())
        ));
    }
}
