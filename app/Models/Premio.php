<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Testing\Fluent\Concerns\Has;

class Premio extends Model
{
    use HasFactory;
    protected $table = 'premio';
    protected $primaryKey = 'id_premio';
    public $incrementing = false;

    public function sorteo(){
        return $this->belongsTo(Sorteo::class, 'id_sorteo', 'id_sorteo');
    }
}
