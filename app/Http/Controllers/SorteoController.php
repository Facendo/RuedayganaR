<?php

namespace App\Http\Controllers;

use App\Models\Sorteo;
use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Ruleta;
use App\Models\Ticket;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class SorteoController extends Controller
{
    
    public function index()
    {
        $ruletas= Ruleta::all();
        $tickets = Ticket::all();
        $clientes = Cliente::orderBy('cantidad_comprados', 'desc')->take(5)->get();
        $sorteos = Sorteo::all();
        return view('index', compact('sorteos','clientes', 'tickets', 'ruletas'));
    }

    

    
    public function store(Request $request)
    {
        //Funcion para almacenar un nuevo sorteo
        $sorteo = new Sorteo();
        $sorteo->sorteo_nombre = $request->sorteo_nombre;
        $sorteo->sorteo_descripcion = $request->sorteo_descripcion;
        $sorteo->precio_boleto_bs = $request->precio_boleto_bs;
        $sorteo->precio_boleto_dolar = $request->precio_boleto_dolar;
        if ($request->hasFile('sorteo_imagen')) {
        $image = $request->file('sorteo_imagen');
        $filename = $image->getClientOriginalName();
        $path = $image->storeAs('sorteo', $filename, 'public'); 
        $sorteo->sorteo_imagen = 'sorteo/' . $filename; 
    }
        $numeros_disponibles = [];
        for ($i = 0; $i <= 9999; $i++) {
            $numeros_disponibles[] = sprintf('%04d', $i);
        }
        $sorteo->numeros_disponibles = json_encode($numeros_disponibles);
        $sorteo->sorteo_fecha_inicio = $request->sorteo_fecha_inicio;
        $sorteo->sorteo_fecha_fin= $request->sorteo_fecha_fin;
        $sorteo->created_at = now();
        $sorteo->updated_at = now();
        $sorteo->save();
        return redirect()->route('sorteo.index');
    }

    
    public function cambio_de_estado(string $id){
        $sorteo = Sorteo::find($id);

        if($sorteo->sorteo_activo == 1){
            $sorteo->sorteo_activo = 0;
        }
        else if($sorteo->sorteo_activo == 0){
            $sorteo->sorteo_activo = 1;
        }
        
        $sorteo->save();
        return redirect()->route('pago.index');
    }
    
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        //Funcion para actualizar un sorteo
        $sorteo = Sorteo::find($request->id_sorteo);
        $sorteo->sorteo_descripcion = $request->sorteo_descripcion;
        $sorteo->sorteo_nombre = $request->sorteo_nombre;
        $sorteo->precio_boleto_bs = $request->precio_boleto_bs;
        $sorteo->precio_boleto_dolar = $request->precio_boleto_dolar;
        
        $sorteo->updated_at = now();
        $sorteo->save();
        return redirect()->route('pago.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sorteo $sorteo)
    {
        //Funcion para eliminar un sorteo
        $sorteo->delete();
        return redirect()->route('pago.index');
    }
}
