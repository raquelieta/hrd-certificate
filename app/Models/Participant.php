<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Participant extends Model
{
    use HasFactory;

    protected $fillable = [
        'training_id',
        'or_number',
        'last_name',
        'first_name',
        'middle_initial',
        'suffix',
        'position',
        'civil_status',
        'employment_status',
        'rank',
        'years_in_service',
        'province',
        'sex',
        'government_sector',
        'agency_name',
        'personal_number',
        'personal_email',
    ];
    protected $appends = [
        'full_name'
    ];

    public function fullName() : Attribute
    {
        return Attribute::make(
            get: function (mixed $value, array $attributes) {
              return  $attributes['last_name'] . ', ' . $attributes['first_name'] . ' ' . $attributes['middle_initial'];
            }
        );
    }

    public function training(){
        return $this->belongsTo(Training::class);
    }
    
}
