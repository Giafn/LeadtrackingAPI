<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Lead extends Model
{
    use HasUuids;
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }

    protected $table = 'leads';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'location',
        'status',
        'due_date',
    ];

    protected $casts = [
        'id' => 'string',
    ];

    public function histories()
    {
        return $this->hasMany(LeadHistory::class, 'lead_id');
    }
}
