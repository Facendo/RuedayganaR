<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
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
