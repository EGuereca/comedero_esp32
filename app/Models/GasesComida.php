<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GasesComida extends Model
{
    use HasFactory;

    protected $table = 'gases_comida';
    protected $fillable = ['valor', 'fecha'];
}
