<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MascotaCerca extends Model
{
    use HasFactory;

    protected $table = 'mascota_cerca';
    protected $fillable = ['valor', 'fecha'];
}
