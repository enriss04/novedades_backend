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
        Schema::create('ventas_mercancias', function (Blueprint $table) {
            $table->id('venta_mercancia_id'); 
            $table->string('venta_id', 50)->nullable();            
            $table->string('inventario_id', 20)->nullable();
            $table->enum('tipo', ['Mayoreo', 'Menudeo'])->default('Menudeo');
            $table->integer('cantidad')->default(0);
            $table->double('precio_unitario', 15, 2)->default(0);
            $table->double('descuento', 15, 2)->default(0);
            $table->double('subtotal', 15, 2)->default(0);
            $table->double('total', 15, 2)->default(0);
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'))->useCurrentOnUpdate(); 
            $table->softDeletes();
            $table->index('venta_id');
            $table->index('inventario_id');
            $table->primary('venta_mercancia_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ventas_mercancias');
    }
};
