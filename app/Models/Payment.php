<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'franchise_application_id',
        'fee_id',
        'amount_paid',
        'paid_at',
        'reviewed_by',
        'or_no',
        'stripe_payment_intent_id',
        'stripe_payment_status'
    ];

    protected $casts = [
        'amount_paid' => 'decimal:2',
        'paid_at' => 'datetime'
    ];

   
    public function franchiseApplication()
    {
        return $this->belongsTo(FranchiseApplication::class, 'franchise_application_id');
    }

    public function fee()
    {
        return $this->belongsTo(Fee::class, 'fee_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
    

}
