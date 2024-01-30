{{-- <!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ trans('panel.site_title') }}</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://unpkg.com/@coreui/coreui@3.2/dist/css/coreui.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet" />
    <link href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.css" rel="stylesheet" />
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet" />
    @yield('styles')
</head>

<body class="header-fixed sidebar-fixed aside-menu-fixed aside-menu-hidden login-page">
    <div class="c-app flex-row align-items-center">
        <div class="container">
            @yield("content")
        </div>

    </div>

    @yield('scripts')
</body>

</html> --}}



<html lang="en">
<head>
    <script type="text/javascript" async="" src="http://www.googletagmanager.com/gtag/js?id=G-WSM4C4P88C&amp;l=dataLayer&amp;cx=c"></script>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">


    {{-- <title>EtranaDez (CSL222) - CSL Behring</title> --}}
    <title>{{ trans('panel.site_title') }}</title>

    <link rel="icon" type="image/x-icon" href="favicon.ico">
    {{-- <link rel="stylesheet" href="{{ config('app.env_app_ui_url')  }}/styles.968506f590c7083a21d0.css"> --}}


    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://unpkg.com/@coreui/coreui@3.2/dist/css/coreui.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet" />
    <link href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.css" rel="stylesheet" />
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet" />
    @yield('styles')


</head>
<body class="header-fixed sidebar-fixed aside-menu-fixed aside-menu-hidden login-page">

        <div class="site-wrap home">

            <header>
                <nav class="navbar navbar-expand-lg ">
                    <div class="container px-0 "><a class="navbar-brand " href="{{ config('app.env_app_ui_url')  }}/home">
                        <img id="logo" alt="Logo image" class="ml-2" src="/img/nucleus-logo.svg"></a>
                    </div>
                </nav>
            </header>

            <main role="main">

                {{-- INSERT CONTENT HERE --}}

                @yield("content")

                {{-- END OF INSERT CONTENT HERE --}}

            </main>
            <footer>
            <div class="container">
                <div class="row">
                    <div class="col-md-6 px-0">Copyright © 2022 Nucleus Global <span>| </span>Version 2.0, January 2022</div>
                    <div class="col-md-6 px-0 d-flex justify-content-end ">
                        <a class="footer-link" href="mailto:etranadezng@nucleusglobalteams.com">Contact Us</a>
                    </div>
                </div>

            </div>
        </footer>

        </div>

        @yield('scripts')
</body>
</html>
