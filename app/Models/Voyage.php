<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voyage extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'vessel_id',
        'code',
        'start',
        'end',
        'status',
        'revenues',
        'expenses',
        'profit',
    ];
}
