<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Locais;

class LocaisController extends Controller
{

    public function index()
    {
        return Locais::all();
    }

    public function store(Request $request)
    {
        Locais::create($request->all());
    }

    public function show($id)
    {
        return Locais::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $local = Locais::findOrFail($id);
        $local->update($request->all());
    }

    public function destroy($id)
    {
        $local = Locais::findOrFail($id);
        $local->delete();
    }
}
