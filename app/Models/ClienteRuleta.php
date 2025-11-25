<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClienteRuleta extends Model
{
    protected $table = 'cliente_ruleta';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'cedula',
        'id_ruleta',
        'oportunidades',
        'residuo',
        'created_at',
        'updated_at'
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cedula', 'cedula');
    }
}
