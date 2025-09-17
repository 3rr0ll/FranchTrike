<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FranchiseApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_number',
        'operator_id',
        'driver_id',
        'application_type',
        'previous_application_id',
        'franchise_no',
        'sticker_no',
        'operator_name',
        'ctc_no',
        'ctc_date_issued',
        'ctc_place_issued',
        'status',
        'rejection_reason',
        'submitted_at',
        'reviewed_at',
        'reviewed_by',
        'franchise_start_date',
        'franchise_end_date',
        'franchise_fee',
        'route_id',
    ];

    protected $casts = [
        'ctc_date_issued' => 'date',
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'franchise_start_date' => 'date',
        'franchise_end_date' => 'date',
        'franchise_fee' => 'decimal:2',
    ];

    // Relationships
    public function operator()
    {
        return $this->belongsTo(Operator::class, 'operator_id', 'operator_id');
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class, 'driver_id', 'driver_id');
    }

    public function previousApplication()
    {
        return $this->belongsTo(FranchiseApplication::class, 'previous_application_id');
    }

    public function renewalApplications()
    {
        return $this->hasMany(FranchiseApplication::class, 'previous_application_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function operatorDocuments()
    {
        return $this->hasMany(OperatorDocument::class);
    }

    public function driverDocuments()
    {
        return $this->hasMany(DriverDocument::class);
    }


    // Accessors
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'submitted' => 'info',
            'under_review' => 'warning',
            'approved' => 'success',
            'rejected' => 'danger',
        ];

        return $badges[$this->status] ?? 'secondary';
    }

    public function getStatusLabelAttribute()
    {
        return ucfirst(str_replace('_', ' ', $this->status));
    }

    public function getApplicationTypeLabelAttribute()
    {
        return ucfirst($this->application_type);
    }

    // Methods
    public function isEditable()
    {
        return in_array($this->status, ['rejected']);
    }

    public function canBeSubmitted()
    {
        $hasRequiredOperatorDocs = $this->operatorDocuments()
            ->whereHas('documentType', function ($query) {
                $query->where('is_required', true)->where('applies_to', 'operator');
            })
            ->where('status', 'approved')
            ->count() > 0;

        $hasRequiredDriverDocs = $this->driverDocuments()
            ->whereHas('documentType', function ($query) {
                $query->where('is_required', true)->where('applies_to', 'driver');
            })
            ->where('status', 'approved')
            ->count() > 0;

        return $this->status === 'submitted' && $hasRequiredOperatorDocs && $hasRequiredDriverDocs;
    }

    public function canBeRenewed()
    {
        return $this->status === 'approved' &&
            !$this->renewalApplications()->whereIn('status', ['submitted', 'under_review', 'approved'])->exists();
    }

    public function isDueForRenewal()
    {
        if (!$this->franchise_end_date || $this->status !== 'approved') {
            return false;
        }

        return $this->franchise_end_date->diffInDays(now()) <= 90;
    }

    public function submit()
    {
        if ($this->canBeSubmitted()) {
            $this->update([
                'submitted_at' => now(),
            ]);

            $this->logStatusChange('submitted', 'submitted', 'Application submitted by operator');
            return true;
        }
        return false;
    }

    public function approve($reviewerId, $franchiseDetails = [])
    {
        $this->update(array_merge([
            'status' => 'approved',
            'reviewed_at' => now(),
            'reviewed_by' => $reviewerId,
        ], $franchiseDetails));

        $this->logStatusChange($this->status, 'approved', 'Application approved');
    }

    public function reject($reviewerId, $reason)
    {
        $this->update([
            'status' => 'rejected',
            'reviewed_at' => now(),
            'reviewed_by' => $reviewerId,
            'rejection_reason' => $reason,
        ]);

        $this->logStatusChange($this->status, 'rejected', $reason);
    }

    protected function logStatusChange($previousStatus, $newStatus, $reason = null)
    {
        // ApplicationStatusHistory::create([
        //     'franchise_application_id' => $this->id,
        //     'previous_status' => $previousStatus,
        //     'new_status' => $newStatus,
        //     'changed_by' => optional(auth())->id(),
        //     'change_reason' => $reason,
        // ]);
    }
    public function route()
    {
        return $this->belongsTo(Route::class);
    }

    public function motorDetail()
    {
        return $this->hasOne(MotorDetail::class, 'franchise_application_id');
    }
    public function motorChangeRequests()
    {
        return $this->hasMany(MotorChangeRequest::class, 'franchise_application_id');
    }

    public function logs()
    {
        return $this->hasMany(FranchiseApplicationLog::class);
    }
}
