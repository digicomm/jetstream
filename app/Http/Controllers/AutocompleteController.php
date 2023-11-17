<?php

namespace App\Http\Controllers;

use App\Models\CI_Item;
use App\Models\SP_BinLocation;
use Illuminate\Http\Request;


class AutocompleteController extends Controller
{
    public function autocompleteBinLocation(Request $request)
    {
        return response()->json(SP_BinLocation::autocompleteBinLocation($request->search));
    }
    public function autocompleteProductCode(Request $request)
    {
        return response()->json(CI_Item::autocompleteProductCode($request->search));
    }
}
