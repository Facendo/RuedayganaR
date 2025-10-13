<?php

namespace App\Http\Controllers;

use App\Models\Ranura;
use Illuminate\Http\Request;

class RanuraController extends Controller
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
        return view('admin.formularioRanura');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $ranura = new Ranura();
        $ranura->id_ruleta = $request->input('id_ruleta');
        $ranura->color = $request->input('color');
        $ranura->type = $request->input('type');
        $ranura->texto = $request->input('texto');
        $ranura->Rate = $request->input('Rate');
        if ($request->hasFile('dir_imagen')) {
            $image = $request->file('dir_imagen');
            $filename = $image->getClientOriginalName();
            $path = $image->storeAs('ranura', $filename, 'public');
            $ranura->dir_imagen = 'ranura/' . $filename;
        }
        $ranura->Blocked = $request->input('Blocked', false);
        $ranura->save();
    }

    /**
     * Display the specified resource.
     */
    public function show(Ranura $ranura)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ranura $ranura)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ranura $ranura)
    {
        $ranura->id_ruleta = $request->input('id_ruleta');
        $ranura->color = $request->input('color');
        $ranura->type = $request->input('type');
        $ranura->texto = $request->input('texto');
        $ranura->Rate = $request->input('Rate');
        if ($request->hasFile('dir_imagen')) {
            $image = $request->file('dir_imagen');
            $filename = $image->getClientOriginalName();
            $path = $image->storeAs('ranura', $filename, 'public');
            $ranura->dir_imagen = 'ranura/' . $filename;
        }
        $ranura->Blocked = $request->input('Blocked', false);
        $ranura->save();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ranura $ranura)
    {
        //
    }
}
