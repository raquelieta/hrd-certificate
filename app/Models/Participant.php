<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Participant extends Model
{
    use HasFactory;

    protected $fillable = [
        'training_id',
        'last_name',
        'first_name',
        'middle_initial',
        'position',
        'employment_status',
        'sex',
        'agency_name',
        'personal_number',
        'personal_email',
    ];

    public function training(){
        return $this->belongsTo(Training::class);
    }
    
}
