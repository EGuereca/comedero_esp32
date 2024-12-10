<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comedero extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table = 'comederos';

    protected $fillable = [
        'usuario_id',
        'mascota_id',
        'numero_serie',
        'cantidad_comida',
        'cantidad_agua',
        'cantidad_agua_servida',
        'cantidad_comida_servida',
        'humedad',
        'gases',
        'temperatua_agua',
        'mascota_cerca',
        'estado'
    ];

    protected $hidden = [
        "deleted_at",
        "created_at",
        "updated_at"
    ];

    /**
     * Relación con el usuario.
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    /**
     * Relación con la mascota.
     */
    public function mascota()
    {
        return $this->belongsTo(Mascota::class, 'mascota_id');
    }

    /**
     * Configuración para disparar eventos al crear un comedero.
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function ($comedero) {
            $usuario = $comedero->usuario; // Relación con el usuario
            $comedero->crearGroupFeed($usuario);
        });
    }

    /**
     * Crea el group feed en Adafruit.
     *
     * @param  \App\Models\User $usuario
     * @return void
     */
    public function crearGroupFeed($usuario)
    {
        $adafruitService = app(\App\Services\AdafruitService::class); // Instancia del servicio Adafruit
        $groupFeed = $adafruitService->createGroupFeed($usuario, $this->id);

        if ($groupFeed) {
            // Si el feed se crea exitosamente, puedes guardar el ID del feed en el modelo
            $this->adafruit_feed_id = $groupFeed->id;
            $this->save();
        }
    }
}
