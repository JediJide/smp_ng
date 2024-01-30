@extends('layouts.app')

@section('content')


<section role="navigation" class="nav navbar navbar-light">
    <div class="container px-0">
        <div id="tab-bar" class="tab-navbar collapse navbar-collapse" ></div>
    </div>
</section>


    <div class="container">

<div class="banner">
        <strong class="header-h1 mb-3">Welcome to the Scientific Platform and Lexicon</strong>
    </div>


<div>
    <p>
        <br />
        <p>Please log in using your email address and chosen password:</p>
        <br />
        <a href="{{ config('app.app_ui_url') }}" style="background: #fc1921; color:#ffffff; box-sizing: border-box; position: relative; -webkit-text-size-adjust: none; border-radius: 4px; display: inline-block; overflow: hidden; text-decoration: none; padding:10px 30px; font-weight:500;">
            Please click here to access the platform
        </a>
    </p>
    <p>Kind Regards,<br />
        <strong>The Scientific Platform and Lexicon team</strong>
    </p>


</div>

  </div>

@endsection
