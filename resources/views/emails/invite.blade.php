@extends('layouts.email')

@section('content')


    <span style="font-size: 1.4em;font-weight:200;line-height: 1.6em;color: #213950;">
        Dear colleague,
    </span><br /><br />

    A user account has been created for you on the Scientific Messenging Platform and Lexicon.
    Before you log in, please click the link below to confirm your registration.
    <br /><br />

    Please note that if you do not confirm your registration within the next
    5 days, your account will expire. If this occurs, please contact <a href="mailto:smp@nucleusglobalteams.com" style="color: #147296;font-weight:400;">smp@nucleusglobalteams.com</a> to request a new account.
    <br /><br />

    <a href="{{$url}}" style="background: #147296; color:#ffffff; box-sizing: border-box; position: relative; -webkit-text-size-adjust: none; border-radius: 4px; display: inline-block; overflow: hidden; text-decoration: none; padding:10px 30px; font-weight:500;">
        Please click here to confirm registration
    </a>


@endsection
