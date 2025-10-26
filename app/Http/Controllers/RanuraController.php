<?php

namespace App\Http\Controllers;

use App\Models\Ranura;
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
        // 1. Validar el ID de la ruleta (obligatorio)
        $ruletaId = $request->input('id_ruleta');
        if (empty($ruletaId)) {
            return response()->json(['error' => '❌ El ID de la Ruleta es obligatorio.'], 400);
        }

        // 2. Obtener los arrays de ranuras y IDs a eliminar
        $slots = $request->input('ranuras', []);
        $deletedIdsString = $request->input('deleted_ids', '');

        // Usamos una transacción para asegurar la atomicidad de las operaciones
        DB::beginTransaction();

        try {
            // =========================================================
            // A. MANEJO DE ELIMINACIONES (DELETE)
            // =========================================================
            if (!empty($deletedIdsString)) {
                $deletedIds = explode(',', $deletedIdsString);
                
                // Buscar y eliminar físicamente las imágenes antes de borrar el registro de la DB
                $ranurasToDelete = Ranura::whereIn('id_ranura', $deletedIds)
                                          ->where('id_ruleta', $ruletaId)
                                          ->get();

                foreach ($ranurasToDelete as $ranura) {
                    if ($ranura->dir_imagen) {
                        // Borra la imagen antigua
                        Storage::disk('public')->delete($ranura->dir_imagen);
                    }
                }
                
                // Eliminar los registros de la base de datos
                Ranura::whereIn('id_ranura', $deletedIds)
                      ->where('id_ruleta', $ruletaId)
                      ->delete();
            }

            // =========================================================
            // B. MANEJO DE ACTUALIZACIONES/INSERCIONES (UPSERT)
            // =========================================================
            foreach ($slots as $uniqueIndex => $slotData) {
                $ranuraId = $slotData['id_ranura'] ?? null;
                $ranura = null;

                // 2.1 Determinar si es UPDATE o INSERT
                if ($ranuraId) {
                    // UPDATE: Buscar el registro existente
                    $ranura = Ranura::where('id_ranura', $ranuraId)
                                    ->where('id_ruleta', $ruletaId)
                                    ->first();
                    
                    // Si no se encuentra (posiblemente borrado o error), forzamos un INSERT.
                    if (!$ranura) {
                        $ranura = new Ranura();
                    }
                } else {
                    // INSERT: Crear una nueva instancia
                    $ranura = new Ranura();
                }

                // Almacenamos la imagen antigua (si existe) por si necesitamos borrarla
                $oldImage = $ranura->dir_imagen;
                
                // 2.2 Asignar datos básicos
                $ranura->id_ruleta = $ruletaId;
                $ranura->color = $slotData['color'] ?? '#000000';
                $ranura->type = $slotData['type'] ?? 'default';
                $ranura->texto = $slotData['texto'] ?? '';
                $ranura->Rate = (int)($slotData['rate'] ?? 0); // Ajustado a 'rate' minúscula del JS
                
                // 2.3 Manejo del checkbox 'Blocked'
                $ranura->Blocked = $request->input("ranuras.{$uniqueIndex}.blocked", 0);


                // 2.4 Manejo de la subida de imagen (dir_imagen)
                if ($request->hasFile("ranuras.{$uniqueIndex}.dir_imagen")) {
                    $image = $request->file("ranuras.{$uniqueIndex}.dir_imagen");
                    
                    // Si hay una imagen antigua, la eliminamos ANTES de guardar la nueva.
                    if ($oldImage) {
                        Storage::disk('public')->delete($oldImage);
                    }
                    
                    // === TU LÓGICA DE GUARDADO ESPECÍFICA MANTENIDA AQUÍ ===
                    $filename = time() . '_' . $uniqueIndex . '.' . $image->getClientOriginalExtension();
                    $path = $image->storeAs('ranura', $filename, 'public'); 
                    $ranura->dir_imagen = $path;
                    // =======================================================
                    
                } 
                // Nota: Si no se sube un archivo, dir_imagen mantendrá su valor original (o null si es nuevo).

                // 2.5 Guardar la ranura (INSERT o UPDATE)
                $ranura->save();
            }

            // 3. Confirmar la transacción
            DB::commit();

        } catch (\Exception $e) {
            // 4. Revertir la transacción si ocurre un error
            DB::rollBack();
            Log::error("Error al guardar ranuras: " . $e->getMessage());
            
            return response()->json([
                'error' => '❌ Ocurrió un error al guardar las ranuras.', 
                'details' => $e->getMessage()
            ], 500);
        }

        // 5. Redirección exitosa
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
