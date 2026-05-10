<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeadStage extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'stage_order',
        'is_default',
    ];

    protected function casts(): array
    {
        return [
            'stage_order' => 'integer',
            'is_default' => 'boolean',
        ];
    }

    public function scopeDefault(Builder $query): Builder
    {
        return $query->where('is_default', true);
    }

    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class);
    }
}
