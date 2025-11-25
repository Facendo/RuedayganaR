<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;
    protected $table = 'admin';
    protected $primaryKey = 'user';
    public $incrementing = false;
    protected $keyType = 'string';

    public function sorteo(){
        return $this->belongsTo(Sorteo::class, 'id_sorteo' , 'id_sorteo');
    }
}
