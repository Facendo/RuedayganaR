<?php

namespace App\Http\Controllers;

use App\Models\Ranura;
use Illuminate\Http\Request;

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
    public function create()
    {
        return view('admin.formularioRanura');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Obtener el ID de la ruleta (que es un campo global)
        $ruletaId = $request->input('id_ruleta');
        if (empty($ruletaId)) {
            // Manejo básico de errores si falta el ID de la ruleta
            return response()->json(['error' => '❌ El ID de la Ruleta es obligatorio.'], 400);
        }
        // 2. Obtener el array principal de ranuras. Usamos 'ranuras' como clave.
        $slots = $request->input('ranuras', []);
        // 3. Iterar sobre cada ranura en el array
        foreach ($slots as $uniqueIndex => $slotData) {
            // $uniqueIndex es la marca de tiempo única generada por JS, 
            // la usamos para construir la clave del archivo correctamente.
            // Crear una nueva instancia de modelo para cada ranura
            $ranura = new Ranura();
            $ranura->id_ruleta = $ruletaId;
            // Asignar datos simples (color, type, texto, Rate)
            $ranura->color = $slotData['color'] ?? '#000000';
            $ranura->type = $slotData['type'] ?? 'default';
            $ranura->texto = $slotData['texto'] ?? '';
            $ranura->Rate = (int)($slotData['Rate'] ?? 0); 
            // 4. Manejo del checkbox 'Blocked'
            // Usamos la notación de puntos anidada para verificar el valor específico del checkbox.
            // Si no está marcado (no existe en el request), se establece a 0 (false).
            $ranura->Blocked = $request->input("ranuras.{$uniqueIndex}.Blocked", 0);
            // 5. Manejo de la subida de imagen (dir_imagen)
            // Usamos $uniqueIndex para apuntar al archivo correcto en el array de archivos.
            if ($request->hasFile("ranuras.{$uniqueIndex}.dir_imagen")) {
                $image = $request->file("ranuras.{$uniqueIndex}.dir_imagen");
                
                // Generar un nombre de archivo seguro y único
                $filename = time() . '_' . $uniqueIndex . '.' . $image->getClientOriginalExtension();
                
                // Guardar el archivo en la carpeta 'public/ranura'
                // Esto devolverá la ruta relativa (ej: ranura/123456_index.jpg)
                $path = $image->storeAs('ranura', $filename, 'public'); 
                
                $ranura->dir_imagen = $path;
            } else {
                // Si no hay archivo, asegúrate de que el campo de la base de datos sea NULL.
                $ranura->dir_imagen = null;
            }
            // 6. Guardar la ranura en la base de datos
            $ranura->save();
        }

        return redirect()->route('pago.index');
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
