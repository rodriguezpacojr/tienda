<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Metodo extends Model
{
    protected $table = "metodo";

    protected $fillable = [
        'nombre', 'imagen'
    ];
}
