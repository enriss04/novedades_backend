<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Persona;

class Cuenta extends Authenticatable implements JWTSubject
{
    // Definir la tabla asociada
    protected $table = 'cuenta';

    // Especificar la clave primaria
    protected $primaryKey = 'cuenta_id';

    protected $fillable = [
        'persona_id',
        'nombre',
        'clave',
        'status', 
        'created_at',
        'updated_at', 
        'deleted_at'
    ];

    protected $hidden = [
        'clave',
        'created_at',
        'updated_at', 
        'deleted_at'
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function persona(): BelongsTo
    {
        return $this->belongsTo(Persona::class, 'persona_id', 'persona_id');
    }

    public function getJWTCustomClaims(): array
    {
        $this->loadMissing('persona');

        return [
            'persona' => [
                'persona_id'        => $this->persona->persona_id,
                'nombre_completo'   => $this->persona->nombre, 
                'correo'            => $this->persona->email,
                'telefono'          => $this->persona->telefono,
            ],
            'status_cuenta' => $this->status,
            'nombre_cuenta' => $this->nombre,
        ];
    }
}
