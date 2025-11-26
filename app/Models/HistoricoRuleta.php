<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoricoRuleta extends Model
{
    use HasFactory;
    protected $table = 'historico_ruleta';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id_ruleta',
        'nombre_ruleta',
        'cedula_jugador',
        'nombre_jugador',
        'telefono',
        'descripcion'
    ];
}
