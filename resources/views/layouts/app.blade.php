<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Plantilla</title>
    <link rel="icon" href="{{ config('SCITS.favicon') }}">

    <meta content="Municipio de Tulancingo de Bravo" name="description" />
    <meta content="Sofipis" name="author" />

    <!-- Fuentes y Estilos -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="{{ asset('css/administration.css') }}" id="app-style" rel="stylesheet" type="text/css" />
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body data-layout="detached" data-topbar="colored">
    @include('layouts.style')
    
    <!-- Preloader -->
    <div id="preloader">
        <div id="ctn-preloader" class="ctn-preloader"> <br><br><br>

            <div class="loader">
                <div class="row">

                    <center> <img src="{{ config('SCITS.logocolor') }}" alt="Logo" style="width: 500px; height: auto;"></center>

                    <center>
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </center>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenido -->
    <div class="container-fluid">
        <div id="layout-wrapper">
            <header id="page-topbar">
                @include('adminmenu.layouts.partials.header.header')

            </header>
            <div class="vertical-menu">
                @include('adminmenu.layouts.partials.vertical-menu')
            </div>
            <div class="main-content">
                <div class="page-content"><br><br><br><br>
                    @yield('content')
                </div>

            </div>

        </div>
    </div>

    <!-- Scripts -->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/eca8824b90.js" crossorigin="anonymous"></script>
    <script src="{{ asset('js/administration.js') }}"></script>

    <footer class="footer">
        @include('adminmenu.layouts.partials.footer')
    </footer>
    @stack('scripts')
</body>

</html>