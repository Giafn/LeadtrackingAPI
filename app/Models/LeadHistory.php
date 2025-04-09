<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class LeadHistory extends Model
{
    use HasUuids;
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }

    protected $table = 'lead_histories';

    protected $fillable = [
        'lead_id',
        'status',
        'changed_at',
        'notes',
    ];

    protected $casts = [
        'id' => 'string',
        'lead_id' => 'string',
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class, 'lead_id');
    }
}
