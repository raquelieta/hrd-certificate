<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Signatory extends Model
{
    use HasFactory;

    protected $fillable = [
        'last_name',
        'first_name',
        'middle_initial',
        'position',
        'designation',
        'is_attested',
        'is_approved',
        'is_msd',
    ];
}
