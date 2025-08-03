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
        'stripe_payment_intent_id',
        'stripe_payment_status'
    ];

    protected $casts = [
        'amount_paid' => 'decimal:2',
        'paid_at' => 'datetime'
    ];

    public function fee()
    {
        return $this->belongsTo(Fee::class);
    }

    public function franchiseApplication()
    {
        return $this->belongsTo(FranchiseApplication::class);
    }
}
