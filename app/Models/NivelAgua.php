<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NivelAgua extends Model
{
    use HasFactory;

    protected $table = 'nivel_agua';
    protected $fillable = ['valor', 'fecha'];
}
