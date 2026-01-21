<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations. 
     */
    public function up(): void
    {
        Schema::create('cuenta', function (Blueprint $table) {
            $table->id('cuenta_id');
            $table->unsignedBigInteger('persona_id')->nullable();
            $table->string('nombre', 20)->nullable();
            $table->string('clave', 255)->nullable();
            $table->enum('status', ['Activo', 'Suspendido', 'Desactivado'])->default('Activo');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'))->useCurrentOnUpdate(); 
            $table->softDeletes(); 
            $table->index('persona_id');
            $table->primary('cuenta_id');
        });

        Schema::create('inventario', function (Blueprint $table) {
            $table->string('inventario_id', 20); 
            $table->string('talla', 15)->nullable();
            $table->string('color', 15)->nullable();
            $table->string('modelo', 20)->nullable();
            $table->string('tipo', 20)->nullable();
            $table->double('precio_mayoreo', 15, 2)->default(0);
            $table->double('precio_menudeo', 15, 2)->default(0);
            $table->integer('existencia')->default(0);
            $table->integer('ingreso')->default(0); 
            $table->integer('salida')->default(0);
            $table->integer('devoluciones')->default(0);
            $table->longText('url')->nullable();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'))->useCurrentOnUpdate(); 
            $table->softDeletes();
            $table->primary('inventario_id');
        });

        Schema::create('personas', function  (Blueprint $table) {
            $table->id('persona_id');
            $table->string('nombre', 100)->nullable();
            $table->string('primer_apellido', 100)->nullable();
            $table->string('segundo_apellido', 100)->nullable();
            $table->string('nombre_completo', 100)->nullable();
            $table->string('correo', 50)->nullable();
            $table->string('telefono', 30)->nullable();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'))->useCurrentOnUpdate(); 
            $table->softDeletes(); 
            $table->primary('persona_id');
        });

        Schema::create('proveedores', function (Blueprint $table) {
            $table->id('proveedor_id');
            $table->unsignedBigInteger('encargado_persona_id')->nullable();
            $table->string('nombre', 100)->nullable();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'))->useCurrentOnUpdate(); 
            $table->softDeletes();
            $table->index('encargado_persona_id');
            $table->primary('proveedor_id');
        });        

        Schema::create('pedidos', function (Blueprint $table) {
            $table->string('pedido_id', 50); 
            $table->unsignedBigInteger('proveedor_id')->nullable();
            $table->dateTime('fecha')->nullable();
            $table->double('descuento', 15, 2)->default(0);
            $table->double('subtotal', 15, 2)->default(0);
            $table->double('total', 15, 2)->default(0);
            $table->double('abono', 15, 2)->default(0);
            $table->enum('status', ['Pendiente', 'Abonado', 'Pagado'])->default('Pendiente');
            $table->longText('url_comprobante_pago')->nullable();
            $table->longText('url_recibo')->nullable();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'))->useCurrentOnUpdate(); 
            $table->softDeletes();
            $table->index('proveedor_id');
            $table->primary('pedido_id');
        });

        Schema::create('pedidos_mercancias', function (Blueprint $table) {
            $table->id('pedido_mercancia_id'); 
            $table->string('pedido_id', 50)->nullable();            
            $table->string('inventario_id', 20)->nullable();            
            $table->double('precio_unitario', 15, 2)->default(0);
            $table->integer('cantidad')->default(0);
            $table->double('descuento', 15, 2)->default(0);
            $table->double('subtotal', 15, 2)->default(0);
            $table->double('total', 15, 2)->default(0);
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'))->useCurrentOnUpdate(); 
            $table->softDeletes();
            $table->index('pedido_id');
            $table->index('inventario_id');
            $table->primary('pedido_mercancia_id');
        });

        Schema::create('ventas', function (Blueprint $table) {
            $table->string('venta_id', 50); 
            $table->dateTime('fecha')->nullable();
            $table->double('descuento', 15, 2)->default(0);
            $table->double('subtotal', 15, 2)->default(0);
            $table->double('total', 15, 2)->default(0);
            $table->longText('comentario')->nullable();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'))->useCurrentOnUpdate(); 
            $table->softDeletes();
            $table->primary('venta_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personas');
        Schema::dropIfExists('cuenta');
        Schema::dropIfExists('proveedores');
        Schema::dropIfExists('inventario');
        Schema::dropIfExists('pedidos');
        Schema::dropIfExists('pedidos_mercancias');
        Schema::dropIfExists('ventas');
    }
};
