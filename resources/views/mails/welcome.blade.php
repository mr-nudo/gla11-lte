@component('mail::message')
Hello **{{$name}}**,  {{-- use double space for line break --}}
Your company account has been created on Glade App!
Thank you for choosing Glade!

Click below to start working right now
@component('mail::button', ['url' => $link])
Login Now!
@endcomponent
Sincerely,
Glade App team.
@endcomponent