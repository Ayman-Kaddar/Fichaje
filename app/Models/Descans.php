<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Descans extends Model
{
    protected $table = "descans";

    protected $fillable = [
        'pausa', 'continuitat', 'fixtage_id'
    ];
}
