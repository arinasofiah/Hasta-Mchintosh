<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class StaffInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $registrationUrl;
    public $inviterName;

    public function __construct(User $user, $registrationUrl, $inviterName = null)
    {
        $this->user = $user;
        $this->registrationUrl = $registrationUrl;
        $this->inviterName = $inviterName;
    }

    public function build()
    {
        return $this->subject('You\'re Invited to Join Hasta Car Rental Staff')
                    ->view('emails.staff-invitation')
                    ->with([
                        'user' => $this->user,
                        'registrationUrl' => $this->registrationUrl,
                        'inviterName' => $this->inviterName,
                    ]);
    }
}