<?php

namespace App\Mail;

use App\Models\FranchiseApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FranchiseStatusUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public FranchiseApplication $application;

    public function __construct(FranchiseApplication $application)
    {
        $this->application = $application->loadMissing(['operator.user', 'driver']);
    }

    public function build()
    {
        $status = $this->application->status;
        $subject = 'Franchise Application ' . ucfirst(str_replace('_', ' ', $status));

        return $this->subject($subject)
            ->view('emails.franchise-status-updated')
            ->with([
                'application' => $this->application,
                'status' => $status,
            ]);
    }
}


