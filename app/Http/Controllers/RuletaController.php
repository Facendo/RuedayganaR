<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\ClienteRuleta;
use App\Models\Ranura;
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
     * 
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $ruleta = new Ruleta();
        $ruleta->id_sorteo = $request->input('id_sorteo');
        $ruleta->nombre = $request->input('nombre');
        $ruleta->cantidad_de_opotunidades_por_dar = $request->input('cantidad_de_opotunidades_por_dar');
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

    public function Spin(Request $request)
    {
        $id_sorteo = $request->input('id_sorteo');
        $cedula = $request->input('cedula');
        $ruleta = Ruleta::where('id_sorteo', $id_sorteo)->first();
        $ranuras = Ranura::where('id_ruleta', $ruleta->id_ruleta)->get();
        $clienteRuleta = ClienteRuleta::where('cedula', $cedula)->first();

        // Lógica para lanzar la ruleta y determinar ganadores
        $total_rate = 0;
        $last_slot = null;
        //Se saca el total de rates
        foreach ($ranuras as $ranura) {
            if(!$ranura->Blocked){
               $total_rate += $ranura->rate;
            }
        }
        //Se genera un numero random entre 1 y el total de rates
        $number_random = rand(1, $total_rate);
        foreach($ranuras as $ranura){
            if(!$ranura->Blocked){
            //Se va restando el rate al numero random hasta que sea menor o igual a 0
            if($number_random <= 0){
                //Se retorna la ranura ganadora
                break;

                if($last_slot->type == 'bancarrota'){
                    //Si la ranura es bancarrota, se eliminan todas las oportunidades del cliente
                    $clienteRuleta->oportunidades -= 1;
                    $clienteRuleta->save();
                    
                    
                }
                elseif($last_slot->type == 'intentar_de_nuevo'){
                    //Si la ranura es intentar de nuevo, no se le resta oportunidades al cliente
                    
                    
                }

                elseif($last_slot->type == 'premio_menor' || $last_slot->type == 'premio_mayor'){
                    $clienteRuleta->oportunidades -= 1;
                    $clienteRuleta->save();
                    
                }
                
            }
            //Si no es menor o igual a 0, se resta el rate de la ranura actual
            else{
                $number_random -= $ranura->rate;
                $last_slot = $ranura->id_ranura;
            }
            
        }

        }

        $ranuraResult = null;
        $ranuraResult=360/ $ruleta->nro_ranuras * ($last_slot);

        return $ranuraResult;
    }

    public function BuildRulet(Request $request)
    {
        $client = ClienteRuleta::where('cedula', $request->input('cedula'))->first();
        $id_sorteo = $request->input('id_sorteo');
        $ruleta = Ruleta::where('id_sorteo', $id_sorteo)->first();
        $ranuras = Ranura::where('id_ruleta', $ruleta->id_ruleta)->get();

        $cliente=Cliente::where('cedula',$request->input('cedula'))->first();
        $clienteReturn=[
            'nombre'=>$cliente->nombre_y_apellido,
            'cedula'=>$cliente->cedula,
            'oportunidades'=>$client->oportunidades,
        ];
        foreach ($ranuras as $ranura) {
            $ranurasReturn[] = [
                
                'type' => $ranura->type,
                'color' => $ranura->color,
            ];
        }
        $ruletaReturn = [
            'id_sorteo' => $ruleta->id_sorteo,
            'id_ruleta' => $ruleta->id_ruleta,
            'nombre' => $ruleta->nombre,
            'dir_imagen' => $ruleta->dir_imagen,
            'nro_ranuras' => $ruleta->nro_ranuras,
        ];
        

       
        return response()->json([
            'ruleta' => $ruletaReturn,
            'ranuras' => $ranurasReturn,
            'cliente'=>$clienteReturn,
        ]);
    }

    

   



}
