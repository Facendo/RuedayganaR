<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\Premio;
use App\Models\Ruleta;
use App\Models\Sorteo;
use App\Models\HistoricoRuleta;
use Illuminate\Http\Request;

class PagoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $Ruletas= Ruleta::all();
        $premios= Premio::all();
        $pagos = Pago::orderBy('id_pago', 'desc')->paginate(3);
        $sorteos = Sorteo::all();
        $historicos = HistoricoRuleta::orderBy('id', 'desc')->paginate(3);
        return view('admin.admin',compact('pagos','premios','sorteos','Ruletas','historicos'));
    }

    public function update(){
        $pago=Pago::find(request('id_pago'));
        $pago->monto = request('monto_edit');
        $pago->cantidad_de_tickets = request('cantidad_edit');
        $pago->save();
        return redirect()->route('pago.index')->with('success', 'Pago actualizado correctamente.');
    }
    
   public function destroy(int $id_pago)
{
    $pago = Pago::find($id_pago);
        $pago->delete();
        return redirect()->route('pago.index')->with('success', 'Pago eliminado correctamente.');
}
public function showComprobante(Request $request)
    {
        $pago = Pago::find($request->id_pago);
        $imagen = $pago->imagen_comprobante;
        return view('admin.showcomprobante', compact('imagen'));
    }
}

