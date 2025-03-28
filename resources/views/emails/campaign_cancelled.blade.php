@component('mail::message')
# Hi {{ $accountName }},

A Campaign has been **cancelled**.

**Campaign Name:** {{ $campaignName }} for {{ $clientName }}

@component('mail::button', ['url' => $campaignUrl])
Login Now
@endcomponent

Kind regards,  
**The Digital Asset Portal Team**  

[Visit Our Website](https://yourwebsite.com/login)

@endcomponent
