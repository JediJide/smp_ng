@extends('layouts.email')

@section('content')

    <span style="font-size: 1.4em;font-weight:200;line-height: 1.6em;color: #213950;">
        We are delighted to welcome you to the Scientific Messenging Platform and Lexicon.
    </span><br /><br />
    To access the platform, please click on the link below and log in using your email address and chosen password:
    <br /><br />



    <a href="{{ config('app.app_ui_url') }}" style="background: #147296; color:#ffffff; box-sizing: border-box; position: relative; -webkit-text-size-adjust: none; border-radius: 4px; display: inline-block; overflow: hidden; text-decoration: none; padding:10px 30px; font-weight:500;">
        Please click here to access the platform
    </a>


@endsection
