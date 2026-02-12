<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <title>{{ config('app.name', 'Municipio de Tulancingo de Bravo') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Municipio de Tulancingo de Bravo" name="description" />
    <meta content="Sofipis" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('images/administration/favicon.png') }}">
    <!-- App Css-->



    <link href="{{ asset('css/administration.css') }}" id="app-style" rel="stylesheet" type="text/css" />

</head>
<body data-layout="detached" data-topbar="colored">

<!-- Loader -->
<div id="preloader">
    <div id="ctn-preloader" class="ctn-preloader">
        <div class="animation-preloader">
            <div class="spinner"></div>
            <div class="txt-loading">
                    <span data-text-preloader="T" class="letters-loading">
                        T
                    </span>
                <span data-text-preloader="U" class="letters-loading">
                        U
                    </span>
                <span data-text-preloader="L" class="letters-loading">
                        L
                    </span>
                <span data-text-preloader="A" class="letters-loading">
                        A
                    </span>
                <span data-text-preloader="N" class="letters-loading">
                        N
                    </span>
                <span data-text-preloader="C" class="letters-loading">
                        C
                    </span>
                <span data-text-preloader="I" class="letters-loading">
                        I
                    </span>
                <span data-text-preloader="N" class="letters-loading">
                        N
                </span>
                <span data-text-preloader="G" class="letters-loading">
                        G
                </span>
                <span data-text-preloader="O" class="letters-loading">
                        O
                </span>
            </div>
            <p class="text-center">Avanza</p>
        </div>
        <div class="loader">
            <div class="row">
                <div class="col-3 loader-section section-left">
                    <div class="bg"></div>
                </div>
                <div class="col-3 loader-section section-left">
                    <div class="bg"></div>
                </div>
                <div class="col-3 loader-section section-right">
                    <div class="bg"></div>
                </div>
                <div class="col-3 loader-section section-right">
                    <div class="bg"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <!-- Begin page -->
    <div id="layout-wrapper">
        <header id="page-topbar">
            @include('administration.layouts.partials.header.header')
        </header> <!-- ========== Left Sidebar Start ========== -->
        <div class="vertical-menu ">
            @include('administration.layouts.partials.vertical-menu')
        </div>
        <!-- Left Sidebar End -->
        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">
            <div class="page-content">
                @yield('content')
            </div>
            <!-- End Page-content -->
            <footer class="footer">
                @include('administration.layouts.partials.footer')
            </footer>
        </div>
        <!-- end main content-->
    </div>
    <!-- END layout-wrapper -->
</div>
<!-- end container-fluid -->
<!-- JAVASCRIPT -->
<!-- <script src="{{ asset('js/app.js') }}"></script> -->
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('js/administration.js') }}"></script>


<script >


</script>
@yield('script')
</body>
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"  ></script>

<!--  Datatables JS-->
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.20/datatables.min.js"></script>
</html>
