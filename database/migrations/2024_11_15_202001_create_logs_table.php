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
        Schema::create('logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('users');
            $table->foreignId('comedero_id')->nullable()->constrained('comederos');
            $table->foreignId('mascota_id')->nullable()->constrained('mascotas');
            $table->text('accion');
            $table->enum('importancia', ['URGENTE', 'INFORMACION', 'ACCION NECESARIA']);
            $table->dateTime('fecha_hora')->nullable();
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
        Schema::dropIfExists('logs');
    }
};
