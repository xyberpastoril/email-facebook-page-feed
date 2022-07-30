@component('mail::message')
# Introduction

The body of your message.
![alt text](https://github.com/n48.png "Test Email")

@component('mail::button', ['url' => ''])
Button Text
@endcomponent

@component('mail::panel')
This is the panel content.
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
