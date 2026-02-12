<div class="dropdown d-inline-block">
    <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown" data-toggle="dropdown"
        aria-haspopup="true" aria-expanded="false">
         
            @if(Auth::user()->sexo == 'Hombre')
        <img src="{{ asset('https://innovaciongubernamental.tulancingo.gob.mx/logos/perfilhombre.png') }}" alt="Header Avatar"
        class="rounded-circle header-profile-user">
        @elseif(Auth::user()->sexo == 'Mujer')
        <img src="{{ asset('https://innovaciongubernamental.tulancingo.gob.mx/logos/perfilmujer.png') }}" alt="Header Avatar"
        class="rounded-circle header-profile-user">
        @else
        <img src="{{ asset('https://innovaciongubernamental.tulancingo.gob.mx/logos/logo2.png') }}" alt="Header Avatar"
        class="rounded-circle header-profile-user">
        @endif
        <span class="d-none d-xl-inline-block ml-1">{{ auth()->user()->name}}</span>
        <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
    </button>
    <div class="dropdown-menu dropdown-menu-right">
        <!-- item-->


        @if(Auth::user()->rol == 1)
            <a class="dropdown-item d-block" href="{{ route('configuracion') }}">
            <i class="bi bi-gear"></i>   Configuraciones
            </a>
            <a class="dropdown-item d-block" href="{{ route('users.index') }}">
             <i class="bi bi-person-add"></i> Usuarios
            </a>
            <a class="dropdown-item d-block" href="{{ route('rol.index') }}">
            <i class="bi bi-person-gear"></i> Rol
            </a>
        @endif
        <div class="dropdown-divider"></div>@guest
        @else
            <li class="nav-item ">
                <a class="nav-link" aria-current="page" onclick="event.preventDefault();
                document.getElementById('logout-form').submit();" href=""><i class="fa fa-sign-out" aria-hidden="true">  </i>
                    Cerrar sesión</a>
            </li>
        @endguest

        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>

    </div>
</div>