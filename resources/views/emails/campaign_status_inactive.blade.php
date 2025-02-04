@component('mail::message')
# Hi {{ $accountName }},

A Campaign has changed status to **Inactive**.

**Campaign Name:** {{ $campaignName }} for {{ $clientName }}

@component('mail::button', ['url' => $campaignUrl])
Login Now
@endcomponent

Kind regards,  
**The Digital Asset Portal Team**  

[Visit Our Website](https://yourwebsite.com/login)

@endcomponent
