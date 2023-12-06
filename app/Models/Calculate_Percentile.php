<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calculate_Percentile extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'percentile',
        'marks',
        'slot'
    ];
}
