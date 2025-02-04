@component('mail::message')
# Hi {{ $accountName }},

A new Campaign has been created.

**Campaign Name:** {{ $campaignName }} for {{ $clientName }}

@component('mail::button', ['url' => $campaignUrl])
Login Now
@endcomponent

Kind regards,  
**The Digital Asset Portal Team**  

[Visit Our Website](https://yourwebsite.com/login)
@endcomponent
