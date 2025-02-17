@component('mail::message')
# Hi {{ $partnerName }},

A new asset has been shared with you on the **Digital Asset Portal**.

You can access the asset from your portal by clicking the button below.

@component('mail::button', ['url' => $assetLink])
View Asset
@endcomponent

If you have any issues accessing the portal, you can log in manually using the link below:

@component('mail::button', ['url' => $websiteUrl])
Login Now
@endcomponent

Kind regards,  
**The Digital Asset Portal Team**  

[Visit Our Website]({{ $websiteUrl }})
@endcomponent
