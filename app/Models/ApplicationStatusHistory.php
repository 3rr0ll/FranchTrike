<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationStatusHistory extends Model
{
    use HasFactory;

    public $timestamps = false; // Only has changed_at

    protected $fillable = [
        'franchise_application_id',
        'previous_status',
        'new_status',
        'changed_by',
        'change_reason',
    ];

    protected $casts = [
        'changed_at' => 'datetime',
    ];

    // Relationships
    public function franchiseApplication()
    {
        return $this->belongsTo(FranchiseApplication::class);
    }

    public function changedBy()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }

    // Accessors
    public function getPreviousStatusLabelAttribute()
    {
        return $this->previous_status ? ucfirst(str_replace('_', ' ', $this->previous_status)) : 'N/A';
    }

    public function getNewStatusLabelAttribute()
    {
        return ucfirst(str_replace('_', ' ', $this->new_status));
    }

    // Boot method
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($history) {
            $history->changed_at = now();
        });
    }
}
