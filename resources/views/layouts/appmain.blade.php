<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Plantilla</title>
<link rel="icon" href="{{config('SCITS.favicon')}}">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- ESTILO -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
@include('layouts.style')
    <div id="app" >
        <nav class="navbar navbar-expand-md navbar-light bg-whites shadow-sm letras">
            <div class="container" >
            @if (Route::has('login'))

@auth
<a class="navbar-brand" href="{{ url('home') }}">
<img src="{{ config('SCITS.logoblanco') }}" srcset="{{ config('SCITS.logoblanco')}}" style="width: 100px; height: 70px;">
              </a>
    
<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
    <span class="navbar-toggler-icon"></span>
</button>
@else
<a class="navbar-brand" href="{{ url('/') }}">
    <img src="{{ config('SCITS.logoblanco') }}" srcset="{{ config('SCITS.logoblanco')}}" style="width: 100px; height: 70px;">
</a>
<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
    <span class="navbar-toggler-icon"></span>
</button>
@endauth

@endif

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                           
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
 
        <main class="py-4 background">
            <div class="container ">
                <div class="row justify-content-center mt-3">
                    <div class="col-md-12">

                        @if ($message = Session::get('success'))
                            <div class="alert alert-success text-center" role="alert">
                                {{ $message }}
                            </div>
                        @endif

                        {{-- <h3 class="text-center mt-3 mb-3">Simple Laravel 10 User Roles and Permissions - <a href="https://www.allphptricks.com/">AllPHPTricks.com</a></h3> --}}
                        @yield('content')

                        <div class="row justify-content-center text-center mt-3">
                            <div class="col-md-12">
                                {{-- <p>Back to Tutorial:
                                    <a href="https://www.allphptricks.com/simple-laravel-10-user-roles-and-permissions/"><strong>Tutorial Link</strong></a>
                                </p>
                                <p>
                                    For More Web Development Tutorials Visit: <a href="https://www.allphptricks.com/"><strong>AllPHPTricks.com</strong></a>
                                </p> --}}
                            </div>
                        </div>


                    </div>
                </div>
            </div>
            
        </main>
        <footer class="footer">
                            @include('adminmenu.layouts.partials.footer')
                        </footer>
    </div>
   
    
</body>

<style>
   
  
   .bg-whites {
   --bs-bg-opacity: 1;
   background-color: var(--color-base);
}
.letras a {
   --bs-bg-opacity: 1;
   color: var(--colorFuenteB);
}

           /* Reset and basic styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body, html {
            height: 100%;
            font-family: 'Georgia', serif;
            color: #b79159; /* color-secundario */
        }

        /* Background styling */
        .background {
            height: 100vh;
            background: linear-gradient(135deg, #b79159 50%, #661a2f 50%); /* color-base y color-primario */
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Content box */
        .content {
            background-color: rgba(255, 255, 255, 0.9);
            border: 1px solid #b79159; /* color-secundario */
            border-radius: 8px;
            padding: 2rem 3rem;
            text-align: center;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }

        .content h1 {
            font-size: 2.8rem;
            font-weight: bold;
            margin-bottom: 1.2rem;
            color: #661a2f; /* color-base */
        }

        .content p {
            font-size: 1.2rem;
            line-height: 1.8;
            color: #87293a; /* color-primario */
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .content h1 {
                font-size: 2.2rem;
            }

            .content p {
                font-size: 1rem;
            }

            .content {
                padding: 1.5rem;
            }
        }
    </style>
</html>
