<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ranura extends Model
{
    use HasFactory;
    protected $table = 'ranura';
    protected $primaryKey = 'id_ruleta';
    public $incrementing = true;
    
    protected $fillable = [
        'id_ruleta',
        'numero_ranura',
        'color_ranura',
        'tipo_ranura',
    ];
    public function ruleta(){
        return $this->belongsTo(Ruleta::class, 'id_ruleta', 'id_ruleta');
    }
}
