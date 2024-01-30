@extends('layouts.email')

@section('content')

    You are receiving this email because we received a password reset request for your account.
    <br /><br />

    <a href="{{$actionUrl}}" style="background: #147296; color:#ffffff; box-sizing: border-box; position: relative; -webkit-text-size-adjust: none; border-radius: 4px; display: inline-block; overflow: hidden; text-decoration: none; padding:10px 30px; font-weight:500;">
        Please click here to change your password.
    </a>
    <br /><br />
    This password reset link will expire in {{$expiryPeriod/1440}} days.
    <br /><br />
    If you did not request a password reset, no further action is required.

    <br /><br />

    <hr>
    If you're having trouble clicking the link above, copy and paste the URL below into your web browser: <span style="font-size:12px;">{{$actionUrl}}</span>
    <hr>

@endsection
