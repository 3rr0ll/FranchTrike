<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperatorClearance extends Model
{
    use HasFactory;

    public $timestamps = false; // Only has updated_at

    protected $fillable = [
        'operator_id',
        'franchise_application_id',
        'barangay_clearance',
        'police_clearance',
        'medical_certificate',
        'drug_test',
        'or_requirement',
        'cr_requirement',
        'ctc_requirement',
        'old_mtop_mayors_permit',
        'proof_of_ownership',
        'cedula',
    ];

    protected $casts = [
        'barangay_clearance' => 'boolean',
        'police_clearance' => 'boolean',
        'medical_certificate' => 'boolean',
        'drug_test' => 'boolean',
        'or_requirement' => 'boolean',
        'cr_requirement' => 'boolean',
        'ctc_requirement' => 'boolean',
        'old_mtop_mayors_permit' => 'boolean',
        'proof_of_ownership' => 'boolean',
        'cedula' => 'boolean',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function operator()
    {
        return $this->belongsTo(Operator::class);
    }

    public function franchiseApplication()
    {
        return $this->belongsTo(FranchiseApplication::class);
    }

    // Methods
    public function getCompletionPercentage()
    {
        $totalRequirements = 10; // Total checkbox items
        $completedRequirements = 0;

        $requirements = [
            'barangay_clearance',
            'police_clearance',
            'medical_certificate',
            'drug_test',
            'or_requirement',
            'cr_requirement',
            'ctc_requirement',
            'old_mtop_mayors_permit',
            'proof_of_ownership',
            'cedula'
        ];

        foreach ($requirements as $requirement) {
            if ($this->$requirement) {
                $completedRequirements++;
            }
        }

        return ($completedRequirements / $totalRequirements) * 100;
    }

    public function isComplete()
    {
        return $this->getCompletionPercentage() >= 90; // Allow some flexibility
    }

    public function getMissingRequirements()
    {
        $requirements = [
            'barangay_clearance' => 'Barangay Clearance',
            'police_clearance' => 'Police Clearance',
            'medical_certificate' => 'Medical Certificate',
            'drug_test' => 'Drug Test',
            'or_requirement' => 'Official Receipt (OR)',
            'cr_requirement' => 'Certificate of Registration (CR)',
            'ctc_requirement' => 'Community Tax Certificate',
            'old_mtop_mayors_permit' => 'Old MTOP and Mayor\'s Permit',
            'proof_of_ownership' => 'Proof of Ownership',
            'cedula' => 'Cedula',
        ];

        $missing = [];
        foreach ($requirements as $field => $label) {
            if (!$this->$field) {
                $missing[] = $label;
            }
        }

        return $missing;
    }
}
