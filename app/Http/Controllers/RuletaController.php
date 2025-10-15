<?php

namespace App\Http\Controllers;

use App\Models\ClienteRuleta;
use App\Models\Ruleta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RuletaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //Retorna todas las ruletas activas
        return Ruleta::where('estado', 'activo')->get();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.formularioRuleta');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $ruleta = new Ruleta();
        $ruleta->id_sorteo = $request->input('id_sorteo');
        $ruleta->nombre = $request->input('nombre');
        $ruleta->cantidad_de_opotunidades_por_dar = $request->input('cantidad_de_opotunidades_por_dar');
        $ruleta->nro_ranuras = $request->input('nro_ranuras');
        if ($request->hasFile('dir_imagen')) {
            $image = $request->file('dir_imagen');
            $filename = $image->getClientOriginalName();
            $path = $image->storeAs('ruleta', $filename, 'public');
            $ruleta->dir_imagen = 'ruleta/' . $filename;
        }
        $ruleta->Condicional_Oportunidades = $request->input('Condicional_Oportunidades', 0);
        $ruleta->save();
        return redirect()->route('pago.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Ruleta $ruleta)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        $ruleta = Ruleta::find($id);
        return view('admin.editarRuleta', compact('ruleta'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $ruleta = Ruleta::find($request->id);
        $ruleta->nombre = $request->nombre;
        $ruleta->cantidad_de_opotunidades_por_dar = $request->cantidad_de_opotunidades_por_dar;
        $ruleta->nro_ranuras = $request->nro_ranuras;
        if ($request->hasFile('dir_imagen')) {
            $image = $request->file('dir_imagen');
            $filename = $image->getClientOriginalName();
            $path = $image->storeAs('ruleta', $filename, 'public');
            $ruleta->dir_imagen = 'ruleta/' . $filename;
        }
        $ruleta->Condicional_Oportunidades = $request->Condicional_Oportunidades;

        $this->ActualizarOportunidades($ruleta->id_sorteo);
        $ruleta->save();
        return redirect()->route('pago.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ruleta $ruleta)
    {
        $ruleta->delete();
        return redirect()->route('pago.index');
    }


    //Implementacion de Actualizacion de Oportunidades de los Clientes

    public function ActualizarOportunidades(int $id_sorteo)
    {
        $ruleta = Ruleta::where('id_sorteo', $id_sorteo)->first();
        $condicional = $ruleta->Condicional_Oportunidades;

        $clientesRuleta = ClienteRuleta::all();
        
        foreach ($clientesRuleta as $cliente) {
            if($cliente->residuo >= $condicional){
                $cliente->oportunidades = floor($cliente->residuo/$condicional) * $ruleta->cantidad_de_opotunidades_por_dar;
                $cliente->residuo = $cliente->residuo %$condicional;
                $cliente->save();
            }
        }
    }
}
