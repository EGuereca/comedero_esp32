<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NivelComida extends Model
{
    use HasFactory;

    protected $table = 'nivel_comida';
    protected $fillable = ['valor', 'fecha'];
}
