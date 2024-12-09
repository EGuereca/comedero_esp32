<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NivelComidaServida extends Model
{
    use HasFactory;

    protected $table = 'nivel_comida_servida';
    protected $fillable = ['valor', 'fecha'];
}
