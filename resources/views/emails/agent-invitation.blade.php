@component('mail::message')
# Welcome to the Help Desk Team!

Hello {{ $user->name }},

You have been invited to join our Help Desk team as an agent. We're excited to have you on board!

Here are your login credentials:
- **Email:** {{ $user->email }}
- **Temporary Password:** {{ $password }}

Please login using these credentials and change your password as soon as possible.

@component('mail::button', ['url' => route('login')])
Login to Help Desk
@endcomponent

If you have any questions, please contact the admin team.

Thanks,<br>
{{ config('app.name') }} Team
@endcomponent 