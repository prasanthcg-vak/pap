<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SharedAssetNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $partner;
    public $assetId;
   

    public function __construct($partner, $assetId )
    {
        $this->partner = $partner;
        $this->assetId = $assetId;
    }

    public function build()
    {
        return $this->subject('New Asset Shared with You')
                    ->markdown('emails.shared_asset_notification')
                    ->with([
                        'partnerName' => $this->partner->name,
                        'assetId' => $this->assetId,
                        'assetLink' => url('/portal/assets/' . $this->assetId), // Adjust as per your route
                        'websiteUrl'=> url('/'),
                    ]);
    }
}
