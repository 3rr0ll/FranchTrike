<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Operator extends Model
{
    use HasFactory;
    protected $primaryKey = 'operator_id';
    protected $fillable = [
        'user_id',
        'operator_id',
        'last_name',
        'first_name',
        'middle_initial',
        'barangay',
        'municipality',
        'province',
        'birth_date',
        'age',
        'sex',
        'civil_status',
        'contact_no',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'age' => 'integer',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function franchiseApplications()
    {
        return $this->hasMany(FranchiseApplication::class, 'operator_id', 'operator_id');
    }
    
    public function documents()
    {
        return $this->hasMany(OperatorDocument::class, 'operator_id', 'operator_id');
    }

    // Accessors
    public function getFullNameAttribute()
    {
        $middleInitial = $this->middle_initial ? ' ' . $this->middle_initial : '';
        return $this->first_name . $middleInitial . ' ' . $this->last_name;
    }

    public function getFullAddressAttribute()
    {
        return $this->barangay . ', ' . $this->municipality . ', ' . $this->province;
    }

    // Methods
    public function getLatestApplication()
    {
        return $this->applications()->latest()->first();
    }

    public function hasActiveApplication()
    {
        return $this->applications()
            ->whereIn('status', ['submitted', 'under_review', 'approved'])
            ->exists();
    }

    public function calculateAge()
    {
        if ($this->birth_date) {
            $this->age = Carbon::parse($this->birth_date)->age;
            $this->save();
        }
    }
    public function drivers()
    {
        return $this->hasMany(Driver::class, 'operator_id', 'operator_id');
    }

    public function operatorDocuments()
    {
        return $this->hasMany(OperatorDocument::class, 'operator_id', 'operator_id');
    }

    // Boot method for auto-calculating age
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($operator) {
            if ($operator->birth_date && !$operator->age) {
                $operator->age = Carbon::parse($operator->birth_date)->age;
            }
        });
    }


    public function getRouteKeyName()
    {
        return 'operator_id';
    }


}
