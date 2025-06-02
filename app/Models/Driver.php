<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Driver extends Model
{
    use HasFactory;

    protected $fillable = [
        'driver_id',
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
        'license_no',
        'license_validity',
        'license_nature',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'license_validity' => 'date',
        'age' => 'integer',
    ];

    // Relationships
    public function applications()
    {
        return $this->hasMany(FranchiseApplication::class);
    }

    public function documents()
    {
        return $this->hasMany(DriverDocument::class);
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
    public function isLicenseValid()
    {
        return $this->license_validity && Carbon::parse($this->license_validity)->isFuture();
    }

    public function getLicenseStatusAttribute()
    {
        if (!$this->license_validity) {
            return 'unknown';
        }

        $validity = Carbon::parse($this->license_validity);

        if ($validity->isPast()) {
            return 'expired';
        } elseif ($validity->diffInDays(now()) <= 30) {
            return 'expiring_soon';
        } else {
            return 'valid';
        }
    }

    // Boot method
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($driver) {
            if ($driver->birth_date && !$driver->age) {
                $driver->age = Carbon::parse($driver->birth_date)->age;
            }
        });
    }
}
