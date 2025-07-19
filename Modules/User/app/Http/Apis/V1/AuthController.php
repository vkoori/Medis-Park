<?php

namespace Modules\User\Http\Apis\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return 'user::index';
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return 'user::create';
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return 'user::show';
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return 'user::edit';
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {}
}
