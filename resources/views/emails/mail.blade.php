@extends('layouts.email')

@section('content')

Dear colleague<br /><br />

We have made some updates to <a href="https://scientificmessagingplatform.eustaging1.n24i.com">scientificmessagingplatform.eustaging1.n24i.com</a> to improve the speed and guarantee the stability of the site.<br /><br />

To access the updated version of the site, you will need to reset your password the first time you log in.<br /><br />

{{--Click here to reset your password and log in.--}}
Please use your email and the temporary password below to access the site.<br />

<strong>Email:</strong> {{ $email }}<br />
<strong>Temporary password:</strong> {{ $password }}<br /><br />

<a href="{{ config('app.app_ui_url') }}" style="background: #147296; color:#ffffff; box-sizing: border-box; position: relative; -webkit-text-size-adjust: none; border-radius: 4px; display: inline-block; overflow: hidden; text-decoration: none; padding:10px 30px; font-weight:500;">
    Please click here to access the platform
</a>
<br />
@endsection
