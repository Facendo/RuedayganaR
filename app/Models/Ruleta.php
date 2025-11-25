<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ruleta extends Model
{
    protected $table = 'ruleta';
    protected $primaryKey = 'id_ruleta';
    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = [
        'id_ruleta',
        'id_sorteo',
        'condicional_oportunidades',
        'created_at',
        'updated_at',
    ];
    
    public function sorteo(){
        return $this->belongsTo(Sorteo::class, 'id_sorteo', 'id_sorteo');
    }
    public function ranuras(){
        return $this->hasMany(Ranura::class, 'id_ruleta', 'id_ruleta');
    }
}
