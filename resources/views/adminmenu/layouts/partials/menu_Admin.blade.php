@if(Auth::user()->rol == 1)

    {{-- --- GRUPO: CATÁLOGOS --- --}}
    @php
        $isCatalogActive = Route::is('secciones.*') || Route::is('espacios_fisicos.*') || Route::is('tipo-documentos.*') || Route::is('lotes.*');
    @endphp
    <li class="list-group-item bg-base border-secondary py-3 px-4">
        <a href="#submenuCatalogo" data-bs-toggle="collapse" 
           aria-expanded="{{ $isCatalogActive ? 'true' : 'false' }}"
           class="d-flex align-items-center text-white text-decoration-none p-2 rounded {{ $isCatalogActive ? '' : 'collapsed' }}">
            <i class="bi bi-folder2-open fs-4 me-2 text-info"></i> 
            Catálogos
            <i class="bi bi-caret-down-fill ms-auto"></i>
        </a>
        <ul class="collapse list-unstyled ms-4 mt-2 {{ $isCatalogActive ? 'show' : '' }}" id="submenuCatalogo">
            <li>
                <a href="{{ route('secciones.index') }}"
                    class="d-flex align-items-center text-white text-decoration-none p-2 rounded {{ Route::is('secciones.*') ? 'fw-bold text-warning bg-primario' : '' }}">
                    <i class="bi bi-diagram-3 fs-5 me-2 text-primary"></i> Secciones
                </a>
            </li>
            <li>
                <a href="{{ route('espacios_fisicos.index') }}"
                    class="d-flex align-items-center text-white text-decoration-none p-2 rounded {{ Route::is('espacios_fisicos.*') ? 'fw-bold text-warning bg-primario' : '' }}">
                    <i class="bi bi-bounding-box fs-5 me-2 text-primary"></i> Espacios físicos
                </a>
            </li>
            <li>
                <a href="{{ route('tipo-documentos.index') }}"
                    class="d-flex align-items-center text-white text-decoration-none p-2 rounded {{ Route::is('tipo-documentos.*') ? 'fw-bold text-warning bg-primario' : '' }}">
                    <i class="bi bi-file-earmark-text fs-5 me-2 text-primary"></i> Documentos
                </a>
            </li>
            <li>
                <a href="{{ route('lotes.index') }}"
                    class="d-flex align-items-center text-white text-decoration-none p-2 rounded {{ Route::is('lotes.*') ? 'fw-bold text-warning bg-primario' : '' }}">
                    <i class="bi bi-grid-3x3-gap fs-5 me-2 text-primary"></i> Lotes
                </a>
            </li>
        </ul>
    </li>

    {{-- --- GRUPO: TITULARES Y BENEFICIARIOS --- --}}
    @php
        $isTitularesActive = Route::is('titulares.*') || Route::is('beneficiarios.*');
    @endphp
    <li class="list-group-item bg-base border-secondary py-3 px-4">
        <a href="#submenuTitulares" data-bs-toggle="collapse" 
           aria-expanded="{{ $isTitularesActive ? 'true' : 'false' }}"
           class="d-flex align-items-center text-white text-decoration-none p-2 rounded {{ $isTitularesActive ? '' : 'collapsed' }}">
            <i class="bi bi-person-badge fs-4 me-2 text-success"></i> 
            Titulares
            <i class="bi bi-caret-down-fill ms-auto"></i>
        </a>
        <ul class="collapse list-unstyled ms-4 mt-2 {{ $isTitularesActive ? 'show' : '' }}" id="submenuTitulares">
            <li>
                <a href="{{ route('titulares.index') }}"
                    class="d-flex align-items-center text-white text-decoration-none p-2 rounded {{ Route::is('titulares.*') ? 'fw-bold text-warning bg-primario' : '' }}">
                    <i class="bi bi-person-circle fs-5 me-2"></i> Lista de Titulares
                </a>
            </li>
            <li>
                <a href="{{ route('beneficiarios.index') }}"
                    class="d-flex align-items-center text-white text-decoration-none p-2 rounded {{ Route::is('beneficiarios.*') ? 'fw-bold text-warning bg-primario' : '' }}">
                    <i class="bi bi-people fs-5 me-2"></i> Beneficiarios
                </a>
            </li>
        </ul>
    </li>

    {{-- --- PRINCIPALES: CONCESIONES Y PAGOS --- --}}
    <li class="list-group-item bg-base border-secondary py-3 px-4">
        <a href="{{ route('concesiones.index') }}"
            class="d-flex align-items-center text-white text-decoration-none p-2 rounded
            {{ Route::is('concesiones.*') ? 'fw-bold text-warning bg-primario' : '' }}">
            <i class="bi bi-journal-bookmark fs-4 me-2 text-warning"></i>
            Concesiones
        </a>
    </li>

    <li class="list-group-item bg-base border-secondary py-3 px-4">
        <a href="{{ route('refrendos.index') }}"
            class="d-flex align-items-center text-white text-decoration-none p-2 rounded
            {{ Route::is('refrendos.*') ? 'fw-bold text-warning bg-primario' : '' }}">
            <i class="bi bi-cash-stack fs-4 me-2 text-danger"></i>
            Refrendos / Pagos
        </a>
    </li>

@elseif(Auth::user()->rol == 2)
    {{-- Lógica para el rol 2 aquí --}}
@endif