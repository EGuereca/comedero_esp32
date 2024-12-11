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
            $table->double('cantidad_comida')->nullable();
            $table->double('cantidad_agua')->nullable();
            $table->double('cantidad_agua_servida')->nullable();
            $table->double('cantidad_comida_servida')->nullable();
            $table->double('humedad')->nullable();
            $table->double('gases')->nullable();
            $table->double('temperatua_agua')->nullable();
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
