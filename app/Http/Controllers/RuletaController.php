<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\ClienteRuleta;
use App\Models\HistoricoRuleta;
use App\Models\Ranura;
use App\Models\Ruleta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\ruletWinnerMail;
use App\Mail\ruletAdminMain;

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
    public function create($id_sorteo)
    {
        return view('admin.formularioRuleta',['id_sorteo'=>$id_sorteo]);
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
        if ($request->hasFile('dir_imagen')) {
            $image = $request->file('dir_imagen');
            $filename = $image->getClientOriginalName();
            $path = $image->storeAs('ruleta', $filename, 'public');
            $ruleta->dir_imagen = 'ruleta/' . $filename;
        }
        $ruleta->Condicional_Oportunidades = $request->Condicional_Oportunidades;

        //Actualizar opotunidades 

        $condicional = $ruleta->Condicional_Oportunidades;

        $clientesRuleta = ClienteRuleta::where('id_ruleta', $ruleta->id_ruleta)->get();
        
        foreach ($clientesRuleta as $cliente) {
            if($cliente->residuo >= $condicional){
                $cliente->oportunidades = floor(($cliente->residuo/$condicional) * $ruleta->cantidad_de_opotunidades_por_dar);
                $cliente->residuo = $cliente->residuo %$condicional;
                $cliente->save();
            }
        }
        $ruleta->save();
        return redirect()->route('pago.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $ruleta = Ruleta::find($request->id_ruleta);
        if ($ruleta) {
            $ruleta->delete();
        }
        return redirect()->route('pago.index');
    }







   public function Spin(Request $request)
{
    $id_sorteo = $request->input('id_sorteo');
    $cedula = $request->input('cedula');

    // 1. Verificación de Ruleta (Evita Error 500 si la ruleta no existe)
    $ruleta = Ruleta::where('id_sorteo', $id_sorteo)->first();
    if (!$ruleta) {
        return response()->json(['error' => 'Sorteo de Ruleta no encontrado.'], 404);
    }
    
    // 2. Verificación de Cliente (Evita Error 500 si el cliente no existe)
    $clienteRuleta = ClienteRuleta::where('cedula', $cedula)->where('id_ruleta', $ruleta->id_ruleta)->first();
    
    if (!$clienteRuleta) {
        return response()->json(['error' => 'Cliente no encontrado.'], 404);
    }

    if ($clienteRuleta->oportunidades <= 0) {
        return response()->json(['error' => 'No tienes oportunidades disponibles para girar la ruleta.'], 403);
    }
    
    // 3. Obtener y Filtrar Ranuras Elegibles (Ranuras no bloqueadas y con rate > 0)
    $ranuras_disponibles = Ranura::where('id_ruleta', $ruleta->id_ruleta)
        ->orderBy('orden') // Mantiene el orden para el cálculo del ángulo
        ->get()
        ->filter(fn ($ranura) => !$ranura->blocked && $ranura->rate > 0);
    
    $total_rate = $ranuras_disponibles->sum('rate');
    
    // Manejo del caso donde no hay nada que seleccionar
    if ($total_rate <= 0) {
        return response()->json(['error' => 'Todas las ranuras elegibles están bloqueadas o sin probabilidad.'], 403);
    }
    
    $number_random = rand(1, $total_rate);
    $last_slot = null;
    
    // 4. Lógica de Selección Ponderada (Solo itera sobre las ranuras elegibles)
    foreach($ranuras_disponibles as $ranura){
        $number_random -= $ranura->rate;
        if($number_random <= 0){
            $last_slot = $ranura;
            break;
        }
    }
    
    // Doble verificación (si falla la lógica de selección, aunque es poco probable aquí)
    if (!$last_slot) {
        return response()->json(['error' => 'Error al seleccionar ranura final.'], 500);
    }
    
    $premio = null;
    
    // 5. Actualización de Oportunidades y Guardado
    if($last_slot->type == 'bancarrota' || $last_slot->type == 'premio_menor' || $last_slot->type == 'premio_mayor'){
        $clienteRuleta->oportunidades -= 1;
        if($last_slot->texto==null){
            $premio = $last_slot->type;
        }
        else{
            $premio= $last_slot->texto;
        }
        $clienteRuleta->save(); // ⬅️ ¡Guardar el cambio en la base de datos!
    }
    elseif($last_slot->type == 'intentar_de_nuevo'){
        $premio = $last_slot->type;
    }
    
    // 6. Cálculo del Ángulo (apuntando al centro de la ranura para mejor animación)
    $ancho_ranura = 360 / $ruleta->nro_ranuras;
    
    // Fórmula que utiliza el id_ranura como índice (1-basado)
    $angulo_centro = ($ancho_ranura * ($last_slot->orden - 1));
    $angle = (int)$angulo_centro;
    // 7. Retorno de Respuesta JSON COMPLETO
    
    $colorRanura = $last_slot->color;
    //Si cae en bancarrota o intentar de nuevo
    if($last_slot->type=='intentar_de_nuevo'|| $last_slot->type=='bancarrota'){

        return response()->json([
            'oportunidades_cliente' => $clienteRuleta->oportunidades,
            'angle' => $angle,
            'premio' => $premio,
            'color' => $colorRanura,
        ]);
    }
        //Si gana un premio mayor o premio menor
        // Tu Controlador

//...
        else{
            $cliente_info = Cliente::where('cedula', $clienteRuleta->cedula)->first();
            
            $correoContent= [
                'nombre' => $cliente_info->nombre_y_apellido,
                'cedula' => $cliente_info->cedula,
                'premio' => $premio,
                'telefono' => $cliente_info->telefono,
                'correo' => $cliente_info->correo
            ];
            
            //Generar historico ruleta

            $historico = new HistoricoRuleta();
            $historico->id_ruleta = $ruleta->id_ruleta;
            $historico->nombre_ruleta = $ruleta->nombre;
            $historico->cedula_jugador = $cliente_info->cedula;
            $historico->nombre_jugador = $cliente_info->nombre_y_apellido;
            $historico->telefono = $cliente_info->telefono;
            $historico->descripcion = "Ganó el premio: " . $premio;
            $historico->save();


            return response()->json([
                'correoContent' => $correoContent, 
                'oportunidades_cliente' => $clienteRuleta->oportunidades,
                'angle' => $angle,
                'premio' => $premio,
                'color' => $colorRanura,
            ]);
            
        }
}


     public function handleMailRequest(Request $request)
    {
        $correoContent = $request->all();

        if (empty($correoContent) || !isset($correoContent['correo'])) {
             return response()->json(['error' => 'Datos de correo faltantes o incorrectos.'], 400);
        }

        try {
            
            $this->sendMails($correoContent); 
            return response()->json(['message' => 'Correos enviados con éxito.'], 200);
        } catch (\Exception $e) {
            // Loguea el error real (problema con el servidor SMTP, etc.)
            Log::error('Error al enviar correos desde handleMailRequest: ' . $e->getMessage());
            // Devuelve un error 500 para que el frontend lo capture.
            return response()->json(['error' => 'Fallo el envío de correos. Verifique logs.'], 500);
        }
    }



    public function sendMails(array $correoContent){

        // Convertimos el array a objeto para usarlo en el constructor Mailable
        $dataObject = (object) $correoContent;
        Mail::to($correoContent['correo'])->send(new ruletWinnerMail($dataObject));
        // Mail::to('Rocktoyonyo@gmail.com')->send(new ruletAdminMain($dataObject));

    }

    public function BuildRulet(Request $request)
    {
        
        $client = ClienteRuleta::where('cedula', $request->input('cedula'))->first();
        $id_sorteo = $request->input('id_sorteo');
        $ruleta = Ruleta::where('id_sorteo', $id_sorteo)->first();
        $ranuras = Ranura::where('id_ruleta', $ruleta->id_ruleta)->get();
        $texto='';
        $cliente=Cliente::where('cedula',$request->input('cedula'))->first();
        $clienteReturn=[
            'nombre'=>$cliente->nombre_y_apellido,
            'cedula'=>$cliente->cedula,
            'oportunidades'=>$client->oportunidades,
        ];
        foreach ($ranuras as $ranura) {
                if($ranura->texto==null){
                    $texto= $ranura->type;
                }
                else{
                    $texto= $ranura->texto;
                }

            $ranurasReturn[] = [
                'color' => $ranura->color,
                'texto' => $texto
            ];
        }
        $ruletaReturn=[
            'nombre' => $ruleta->nombre,
            'id_sorteo'=>$ruleta->id_sorteo,
            'id_ruleta'=>$ruleta->nro_ranuras,
            'dir_imagen'=>$ruleta->dir_imagen,
            'nro_ranuras'=>$ruleta->nro_ranuras
        ];
        
        return response()->json([
            'ruleta'=> $ruletaReturn,
            'ranuras' => $ranurasReturn,
            'cliente'=> $clienteReturn,
        ]);
    }



    public function ActivarRuleta($id_ruleta){
        $ruleta=Ruleta::find($id_ruleta);

         if($ruleta->activo == 1){
            $ruleta->activo = 0;
        }
        else if($ruleta->activo == 0){
            $ruleta->activo = 1;
        }
        $ruleta->save();
        return redirect()->route('pago.index');
    }
    

   



}
