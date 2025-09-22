<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'status',
        'created_by',
        'settings',
    ];

    protected function casts(): array
    {
        return [
            'settings' => 'array',
        ];
    }

    // Relationships
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function bugs()
    {
        return $this->hasMany(Bug::class);
    }

    // Helper methods
    public function getBugCountAttribute(): int
    {
        return $this->bugs()->count();
    }

    public function getOpenBugCountAttribute(): int
    {
        return $this->bugs()->whereIn('status', ['open', 'in_progress'])->count();
    }

    public function getResolvedBugCountAttribute(): int
    {
        return $this->bugs()->whereIn('status', ['resolved', 'closed'])->count();
    }
}
