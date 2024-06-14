<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

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

    protected $appends = [
        'full_name'
    ];
    
    public function fullName() : Attribute
    {
        return Attribute::make(
            get: function (mixed $value, array $attributes) {
              return  strtoupper($attributes['first_name']) . ' ' . strtoupper($attributes['middle_initial']) . ' ' . strtoupper($attributes['last_name']);
            }
        );
    }
}
