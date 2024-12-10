<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comederos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('users');
            $table->foreignId('mascota_id')->constrained('mascotas');
            $table->decimal('cantidad_comida', 4, 1)->nullable();
            $table->decimal('cantidad_agua', 4, 1)->nullable();
            $table->decimal('cantidad_agua_servida', 4, 1)->nullable();
            $table->decimal('cantidad_comida_servida', 4, 1)->nullable();
            $table->decimal('humedad', 4, 1)->nullable();
            $table->decimal('gases', 4, 1)->nullable();
            $table->decimal('temperatua_agua', 4, 1)->nullable();
            $table->boolean('mascota_cerca')->nullable();
            $table->enum('estado', ['ACTIVO', 'INACTIVO', 'DEFECTUOSO']);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comederos');
    }
};
