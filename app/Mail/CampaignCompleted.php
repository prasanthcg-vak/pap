<?php 
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CampaignCompleted extends Mailable
{
    use Queueable, SerializesModels;

    public $accountName;
    public $campaignName;
    public $clientName;
    public $campaignUrl;

    /**
     * Create a new message instance.
     */
    public function __construct($accountName, $campaignName, $clientName, $campaignUrl)
    {
        $this->accountName = $accountName;
        $this->campaignName = $campaignName;
        $this->clientName = $clientName;
        $this->campaignUrl = $campaignUrl;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Campaign Completed')
                    ->markdown('emails.campaign_completed');
    }
}
