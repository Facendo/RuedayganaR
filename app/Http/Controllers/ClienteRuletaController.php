<?php

namespace App\Http\Controllers;

use App\Models\ClienteRuleta;
use App\Models\Ruleta;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClienteRuletaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function CalcularResiduo(int $cedulaCliente, int $CantidadComprados, int $id_sorteo)
    {
      
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function ActualizarOportunidades(int $cedulaCliente, int $id_sorteo)
    {
        $ruleta = Ruleta::where('id_sorteo', $id_sorteo)->first();
        $condicional = $ruleta->condicional_oportunidades;

        ClienteRuleta::where('residuo', '>=', $condicional)
            ->update([
                'oportunidades' => DB::raw("oportunidades + FLOOR(residuo / {$condicional})"),
                'residuo' => DB::raw("residuo % {$condicional}"),
            ]);
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
    public function show(ClienteRuleta $clienteRuleta)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ClienteRuleta $clienteRuleta)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ClienteRuleta $clienteRuleta)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ClienteRuleta $clienteRuleta)
    {
        //
    }
}
