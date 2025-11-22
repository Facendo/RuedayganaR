<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\ClienteRuleta;
use App\Models\Ruleta;
use App\Models\Pago;
use App\Models\Sorteo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File\UploadedFile;
use Illuminate\Http\UploadedFile as HttpUploadedFile;

class ClienteController extends Controller
{
    public function index(int $id_sorteo)
    {
        $clientes = Cliente::all();
        $sorteo= Sorteo::find($id_sorteo);
        return view('compra', compact('sorteo', 'clientes'));
    }
    
    public function store(Request $request)
    {   
        if(Cliente::where('cedula', $request->cedula)->exists()){
            $clienteregistrado = Cliente::where('cedula', $request->cedula)->first();
            $clienteregistrado->fecha_de_pago = $request->fecha_de_pago;
            $clienteregistrado->save();
            $clienteregistrado->id_sorteo = $request->id_sorteo;
            
            //Cliente registrado pero no como Cliente de ruleta
            $ruleta = Ruleta::where('id_sorteo', $request->id_sorteo)->first();

                if ($ruleta && $ruleta->activo) {
                    // 2. Optimizar: Intentar encontrar directamente el ClienteRuleta.
                    // Usamos el id de la ruleta encontrado ($ruleta->id) y la cédula del request.
                    $clienteRuletaExistente = ClienteRuleta::where('id_ruleta', $ruleta->id) // Asumiendo que el ID de la tabla Ruleta es 'id'
                                                        ->where('cedula', $request->cedula)
                                                        ->first();

                    // 3. Registrar al cliente solo si NO existe
                    if (!$clienteRuletaExistente) {
                        $clienteRuleta = new ClienteRuleta();
                        $clienteRuleta->cedula = $request->cedula;
                        // Ojo: Asumo que el campo 'id_ruleta' en ClienteRuleta es la FK al campo 'id' de Ruleta
                        $clienteRuleta->id_ruleta = $ruleta->id_ruleta; 
                        $clienteRuleta->oportunidades = 0;
                        $clienteRuleta->residuo = 0;
                        // created_at y updated_at se gestionan automáticamente por Eloquent, 
                        // pero puedes mantenerlas si tienes un caso de uso especial.
                        $clienteRuleta->created_at = now();
                        $clienteRuleta->updated_at = now(); 
                        $clienteRuleta->save();
                        
                        // Retornar éxito, etc.
                    }
                    
                    // Retornar mensaje de que ya estaba registrado, etc.

                }
        }
        else{
            $cliente = new Cliente();
            $cliente->cedula = $request->cedula;
            $cliente->nombre_y_apellido = $request->nombre_y_apellido;
            $cliente->telefono = $request->telefono;
            $cliente->correo = $request->correo;
            $cliente->cantidad_comprados = 0;
            $cliente->fecha_de_pago = $request->fecha_de_pago; 
            $cliente->id_sorteo = $request->id_sorteo;
            $cliente->save();
            
            //Crear clienteRuleta si la ruleta esta creada para este sorteo
            if(Ruleta::where('id_sorteo',$request->id_sorteo)->exists()){
                $ruleta=Ruleta::where('id_sorteo', $request->id_sorteo)->first();
                if($ruleta->activo){
                    $clienteRuleta= new ClienteRuleta();
                    $clienteRuleta->cedula = $request->cedula;
                    $clienteRuleta->id_ruleta = $ruleta->id_ruleta;
                    $clienteRuleta->oportunidades = 0;
                    $clienteRuleta->residuo = 0;
                    $clienteRuleta->created_at = now();
                    $clienteRuleta->updated_at = now();
                    $clienteRuleta->save();       
                }
            }

           
            
        }
        $pago = new Pago();
        if(Pago::where('referencia', $request->referencia)->exists()){
            return redirect()->back()->with('error', 'La referencia ya existe.');
        }
        
        else{
            $pago->cedula_cliente = $request->cedula;
            $pago->referencia = $request->referencia;
            $pago->id_sorteo = $request->id_sorteo;
            $pago->monto = $request->monto;
            $pago->cantidad_de_tickets = $request->cantidad_de_tickets;
            $pago->descripcion = $request->descripcion;
            $pago->nro_telefono= $request->telefono;
            $pago->fecha_pago = $request->fecha_de_pago;
            $pago->metodo_de_pago = $request->metodo_pago_seleccionado;
            $pago->estado_pago = "pendiente";
                if ($request->hasFile('imagen_comprobante')) {
                $image = $request->file('imagen_comprobante');
                $originalName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $extension = $image->getClientOriginalExtension();
                $filename = $originalName . "_" . $request->referencia . '.' . $extension;
                $path = $image->storeAs('comprobantes', $filename, 'public');
                $pago->imagen_comprobante = 'comprobantes/' . $filename;
                }
            $pago->descripcion = " Pago de " . $request->cantidad_de_tickets . " tickets". " En la fecha " . $request->fecha_de_pago;
            $pago->save();
            return redirect()->route('sorteo.index');
        }
    }

   
}
