<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Testing\Fluent\Concerns\Has;

class Ticket extends Model
{
    use HasFactory;
    protected $table = 'ticket'; 

    public function sorteo(){
        return $this->belongsTo(Sorteo::class, 'id_sorteo', 'id_sorteo');
    }

    public function pago(){
        return $this->hasMany(Pago::class, 'id_ticket', 'id_ticket');
    }
}
