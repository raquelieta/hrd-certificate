<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Training extends Model
{
    use HasFactory, SoftDeletes;

    protected $casts = [
        'training_type' => 'json',
        'training_venue' => 'json',
        'training_speakers' => 'array',
    ];

    protected $fillable = [
        'training_code',
        'training_name',
        'description',
        'training_start',
        'training_end',
        'training_hours',
        'training_type',
        'training_venue',
        'training_address',
        'training_speakers',
        'issuance_date',
        'director_id',
        'hr_head_id',
    ];

    public function participants() {
        return $this->hasMany(Participant::class);
    }
}
