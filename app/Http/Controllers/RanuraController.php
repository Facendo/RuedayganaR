<?php

namespace App\Http\Controllers;

use App\Models\Ranura;
use App\Models\Ruleta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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
    public function create($id_ruleta)
    {   
        $ranuras= Ranura::where('id_ruleta',$id_ruleta)->get();
        return view('admin.formularioRanura', compact('ranuras','id_ruleta'));
    }

    /**
     * Store a newly created resource in storage.
     */
   
    public function store(Request $request)
    {
        $id_ruleta = $request->input('id_ruleta');

        // 1. Validar ID de Ruleta
        if (empty($id_ruleta)) {
            return redirect()->back()->with('error', '❌ El ID de la ruleta es obligatorio para guardar las ranuras.');
        }

        $slots = $request->input('ranuras', []);

        // 2. INICIAR TRANSACCIÓN (Garantiza que la operación es todo o nada)
        DB::beginTransaction();

        try {
            // --- FASE 1: ELIMINACIÓN SEGURA ---
            
            // 2.1 Buscar y eliminar físicamente las imágenes de las ranuras existentes
            $ranurasToDelete = Ranura::where('id_ruleta', $id_ruleta)->get();

            foreach ($ranurasToDelete as $ranura) {
                if ($ranura->dir_imagen) {
                    // Borra la imagen antigua del disco
                    Storage::disk('public')->delete($ranura->dir_imagen);
                }
            }
            
            // 2.2 Eliminar los registros de la base de datos (SELECTIVE DELETE)
            Ranura::where('id_ruleta', $id_ruleta)->delete();
            
            
            // --- FASE 2: INSERCIÓN DE NUEVOS REGISTROS ---
            
            $orden = 0; // Contador para la nueva columna 'orden' (1, 2, 3...)
            $cont_ranuras=0;
            foreach($slots as $uniqueIndex => $ranuraData) {
                $orden++; // Incrementar el valor de orden para esta ranura
                $cont_ranuras++;
                $newSlot = new Ranura();
                
                // 3. ASIGNACIÓN DE DATOS (Usando sintaxis de array [])
                $newSlot->id_ruleta = $id_ruleta;
                $newSlot->orden     = $orden; // 🌟 Nuevo campo de orden secuencial
                $newSlot->type      = $ranuraData['type'] ?? 'default'; 
                $newSlot->color     = $ranuraData['color'] ?? '#000000';
                $newSlot->texto     = $ranuraData['texto'] ?? null;
                $newSlot->Rate      = (int)($ranuraData['rate'] ?? 0);
                $newSlot->Blocked   = (int)($ranuraData['blocked'] ?? 0); 
                
                // 4. MANEJO DE LA SUBIDA DE IMAGEN
                // Usamos $uniqueIndex para identificar el archivo subido en el Request
                if ($request->hasFile("ranuras.{$uniqueIndex}.dir_imagen")) {
                    $image = $request->file("ranuras.{$uniqueIndex}.dir_imagen");
                    
                    // Lógica de guardado
                    $filename = time() . '_' . $uniqueIndex . '.' . $image->getClientOriginalExtension();
                    $path = $image->storeAs('ranura', $filename, 'public'); 
                    $newSlot->dir_imagen = $path;
                }
                
                $newSlot->save();
            }

            //Asignacion de contador de ranuras
            $ruleta=Ruleta::find($id_ruleta);
            $ruleta->nro_ranuras=$cont_ranuras;
            $ruleta->save();


            // 5. CONFIRMAR la transacción
            DB::commit();

        } catch (\Exception $e) {
            // 6. REVERTIR si algo falla
            DB::rollBack();
            Log::error("Error al guardar ranuras: " . $e->getMessage());
            
            return redirect()->back()
                             ->with('error', '❌ Error al guardar las ranuras. Se ha revertido la operación. Detalles: ' . $e->getMessage());
        }

        // 7. Redirección exitosa
        return redirect()->route('pago.index')
            ->with('success', '✅ Las ranuras de la ruleta se han guardado exitosamente.');
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
