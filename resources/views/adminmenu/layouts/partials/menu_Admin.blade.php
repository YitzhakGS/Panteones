@if(Auth::user()->rol == 1)

<li class="list-group-item bg-base border-secondary py-2 px-4">
    <a href="{{ route('secciones.index') }}"
        class="d-flex align-items-center text-white text-decoration-none p-2 rounded
        {{ Route::is('secciones.*') ? 'fw-bold text-warning bg-primario' : '' }}">
        <i class="bi bi-diagram-3 fs-5 me-2"></i>
        Secciones
    </a>
</li>

<li class="list-group-item bg-base border-secondary py-2 px-4">
    <a href="{{ route('espacios_fisicos.index') }}"
        class="d-flex align-items-center text-white text-decoration-none p-2 rounded
        {{ Route::is('espacios_fisicos.*') ? 'fw-bold text-warning bg-primario' : '' }}">
        <i class="bi bi-bounding-box fs-5 me-2"></i>
        Espacios físicos
    </a>
</li>

<li class="list-group-item bg-base border-secondary py-2 px-4">
    <a href="{{ route('tipo-documentos.index') }}"
        class="d-flex align-items-center text-white text-decoration-none p-2 rounded
        {{ Route::is('tipo-documentos.*') ? 'fw-bold text-warning bg-primario' : '' }}">
        <i class="bi bi-file-earmark-text fs-5 me-2"></i>
        Tipos de documento
    </a>
</li>


<li class="list-group-item bg-base border-secondary py-3 px-4">
    <a href="{{ route('titulares.index') }}"
        class="d-flex align-items-center text-white text-decoration-none p-2 rounded
        {{ Route::is('titulares.*') ? 'fw-bold text-warning bg-primario' : '' }}">
        <i class="bi bi-person-badge fs-4 me-2"></i>
        Titulares
    </a>
</li>

<li class="list-group-item bg-base border-secondary py-3 px-4">
    <a href="{{ route('lotes.index') }}"
        class="d-flex align-items-center text-white text-decoration-none p-2 rounded
        {{ Route::is('lotes.*') ? 'fw-bold text-warning bg-primario' : '' }}">
        <i class="bi bi-grid-3x3-gap fs-4 me-2"></i>
        Lotes
    </a>
</li>

<li class="list-group-item bg-base border-secondary py-3 px-4">
    <a href="{{ route('concesiones.index') }}"
        class="d-flex align-items-center text-white text-decoration-none p-2 rounded
        {{ Route::is('concesiones.*') ? 'fw-bold text-warning bg-primario' : '' }}">
        <i class="bi bi-journal-bookmark fs-4 me-2"></i>
        Concesiones
    </a>
</li>

@elseif(Auth::user()->rol == 2)

@endif