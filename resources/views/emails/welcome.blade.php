@component('mail::message')
# Hi {{ $accountName }},

Welcome to the **Digital Asset Portal**.

Please find below your **Portal password**:  
**Password:** `{{ $password }}`

Please ensure you keep this password stored in a safe place.  
If you lose or forget your password, you will be able to generate a new one from the website login page.

@component('mail::button', ['url' => $loginUrl])
Login Now
@endcomponent

Kind regards,  
**The Digital Asset Portal Team**  

[Visit Our Website]({{ $loginUrl }})
@endcomponent
