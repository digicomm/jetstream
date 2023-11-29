<?php

namespace App\Http\Controllers;

use App\Models\SP_InventoryOnHandC;
use Illuminate\Http\Request;
use Inertia\Inertia;

class IN_QUAController extends Controller
{
    public function index()
    {
        return Inertia::render('Inventory/QUA');
    }

    public function create()
    {
        return response()->json(SP_InventoryOnHandC::getAllQUA());
    }

    public function store(Request $request)
    {
    }

    public function show($id)
    {
    }

    public function edit($id)
    {
    }

    public function update(Request $request, $id)
    {
    }

    public function destroy($id)
    {
    }
}
