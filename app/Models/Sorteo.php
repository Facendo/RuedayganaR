<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sorteo extends Model
{
    Use HasFactory;
    protected $table = 'sorteo';
    protected $primaryKey = 'id_sorteo';

    protected $fillable = [
        'sorteo_nombre',
        'sorteo_fecha_inicio',
        'sorteo_fecha_fin',
        'sorteo_descripcion',
        'precio_boleto_bs',
        'precio_boleto_dolar',
        'sorteo_imagen',
        'sorteo_activo',
    ];

    public function admin(){
        return $this->hasMany(Admin::class, 'user', 'user');
    }   

    public function premio(){
        return $this->hasMany(Premio::class, 'id_sorteo' , 'id_sorteo');
    }
    public function ticket(){
        return $this->hasMany(Ticket::class, 'id_sorteo' , 'id_sorteo');
    }
}
