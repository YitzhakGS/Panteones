<div data-simplebar class="h-100 bg-whites">

    <div class="user-wid text-center py-4">
        <div class="user-img">
            @if(Auth::user()->sexo == 'Hombre')
            <img src="{{ asset('https://innovaciongubernamental.tulancingo.gob.mx/logos/perfilhombre.png') }}" alt=""
                class="avatar-md mx-auto rounded-circle">
            @elseif(Auth::user()->sexo == 'Mujer')
            <img src="{{ asset('https://innovaciongubernamental.tulancingo.gob.mx/logos/perfilmujer.png') }}" alt=""
                class="avatar-md mx-auto rounded-circle">
            @else
            <img src="{{ asset('https://innovaciongubernamental.tulancingo.gob.mx/logos/logo2.png') }}" alt=""
                class="avatar-md mx-auto rounded-circle">
            @endif

        </div>

        <div class="mt-3">
            <a href="#" class="text-white font-weight-medium font-size-16">{{ auth()->user()->name}}</a>
            <p class="text-white mt-1 mb-0 font-size-13">
                <strong> {{Auth::user()->rols->name}} </strong>
                <br>
                @php
                echo "" . date(' H:i:s');
                @endphp
            </p>
            
        </div>

    </div>

    <!--- Sidemenu -->
    <div id="sidebar-menu">
        <!-- Left Menu Start -->
        <ul class="metismenu list-unstyled" id="side-menu">
            <li class="menu-title">Administración</li>


            @include('adminmenu.layouts.partials.menu_Admin')










            <!--
            @if(Auth::user()->hasRole('user'))
              @include('administration.layouts.partials.item-dashboard')
              @include('administration.layouts.partials.item-payments')
              @include('administration.layouts.partials.item-formalities')

            @endif

            @if(Auth::user()->hasRole('predial'))
              @include('administration.layouts.partials.item-dashboard')
              @include('administration.layouts.partials.item-predial')
              @endif

              @if(Auth::user()->hasRole('admin'))
              @include('administration.layouts.partials.item-dashboard')
              @include('administration.layouts.partials.item-statistic')
              @include('administration.layouts.partials.item-payments')
              @include('administration.layouts.partials.item-formalities')
              @include('administration.layouts.partials.item-users')
              @include('administration.layouts.partials.item-predial')


              @endif -->




        </ul>
    </div>
    <!-- Sidebar -->
</div>
<style>
    .bg-whites {
        --bs-bg-opacity: 1;
        background-color: var(--color-base);
    }

    .menu-title {
        --bs-bg-opacity: 1;
        color: var(--colorFuenteB);
    }
</style>