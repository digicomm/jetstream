<?php

namespace App\Http\Controllers;

use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\UserProfile\UpdateLoginMethods;
use App\Models\User;
use Illuminate\Http\Request;

class UpdateUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        switch($request->action) {
            case 'enable':
                $user = User::find($request->user()->id);
                $user->microsoft_id = $request->account['localAccountId'];
                $user->save();
                break;
            case 'disable':
                $user = User::find($request->user()->id);
                $user->microsoft_id = null;
                $user->save();
                break;
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function updatePhoto(User $user, $input) {
        $user->updateProfilePhoto($input['photo']);
        $user->save();
    }
}
