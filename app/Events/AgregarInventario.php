<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class AgregarInventario
{
    use Dispatchable, SerializesModels;

    public $inventario;

    public function __construct($inventario)
    {
        $this->inventario = $inventario;
    }
}
