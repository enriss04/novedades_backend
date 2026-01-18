<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    protected $table = 'personas'; // Nombre de la tabla
    
    protected $primaryKey = 'persona_id'; // Llave primaria

    protected $fillable = [
        'nombre', 
        'primer_apellido', 
        'segundo_apellido',
        'nombre_completo',
        'correo', 
        'telefono', 
        'created_at',
        'updated_at', 
        'deleted_at'
    ];

    protected $hidden = [         
        'created_at',
        'updated_at', 
        'deleted_at'
    ];

}
