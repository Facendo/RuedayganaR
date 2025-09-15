<?php

namespace App\Http\Controllers;

use App\Models\Premio;
use Illuminate\Http\Request;

class PremioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //Vista para listar los premios
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //Vista para crear un nuevo premio
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $premio = new Premio();
        $premio->id_sorteo = $request->id_sorteo;
        $premio->premio_nombre = $request->premio_nombre;
        $premio->premio_descripcion = $request->premio_descripcion;
        if ($request->hasFile('premio_imagen')) {
            $image = $request->file('premio_imagen');
            $filename = $image->getClientOriginalName();
            $path = $image->storeAs('premios', $filename, 'public'); // Guarda con el nombre original
            $premio->premio_imagen = 'premios/' . $filename; // Guarda la ruta con el nombre original
        }
        $premio->created_at = now();
        $premio->updated_at = now();
        $premio->save();
        return redirect()->route('pago.index')->with('success', 'Premio creado exitosamente');
    }

   

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Premio $premio)
    {
        //Vista para editar un premio
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Premio $premio)
    {
        $premio->id_sorteo = $request->id_sorteo;
        $premio->premio_nombre = $request->premio_nombre;
        $premio->premio_descripcion = $request->premio_descripcion;
        $premio->premio_imagen = $request->premio_imagen;
        $premio->created_at = now();
        $premio->updated_at = now();
        $premio->save();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Premio $premio)
    {
        //funcion para eliminar un premio
        $premio->delete();
    }
}
