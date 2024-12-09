<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Humedad extends Model
{
    use HasFactory;

    protected $table = 'humedad';
    protected $fillable = ['valor', 'fecha'];
}
