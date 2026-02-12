@extends('layouts.app')

@section('title', 'Configuración de Colores')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box mb-0 d-flex align-items-center justify-content-between">
            <h4 class="page-title mb-0 font-size-18">
            <i class="bi bi-gear"></i> Configuración
            </h4>
        </div>
    </div>
</div>

    <div class="row justify-content-center mt-4">
        <div class="col-md-6">
            @if ($errors->has('error'))
                <div class="alert alert-danger">
                    {{ $errors->first('error') }}
                </div>
            @endif
            @if(session('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
            @endif
      
        </div>
    </div>
    <div class="row justify-content-center ">
        <div class="col-md-6 mt-4">
            <div class="d-flex justify-content-center">
                <div class="card mx-2" style="width: 100%;">
                    <div class="card-body">
                        <form action="{{ route('guardarConfiguracion') }}" method="POST" class="mx-3">
                            <h1 class="text-center">Configuración de Colores</h1>
                            @csrf
                            <div class="form-group" id="color-base-group">
                                <label for="color-base" class="mb-2">Color Base:</label> <!-- Agregamos margen inferior -->
                                <div class="input-group">
                                    <input type="color" class="form-control form-control-lg" id="color-base" name="color-base" value="{{ config('SCITS.color-base') }}" required>
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" id="toggle-color-base" type="button"><i class="bi bi-paint-bucket" style="font-size: 1.5rem;"></i></button>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group" id="color-primario-group">
                                <label for="color-primario" class="mb-2">Color Primario:</label> <!-- Agregamos margen inferior -->
                                <div class="input-group">
                                    <input type="color" class="form-control form-control-lg" id="color-primario" name="color-primario" value="{{ config('SCITS.color-primario') }}" required>
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" id="toggle-color-primario" type="button"><i class="bi bi-paint-bucket" style="font-size: 1.5rem;"></i></button>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group" id="color-secundario-group">
                                <label for="color-secundario" class="mb-2">Color Secundario:</label> <!-- Agregamos margen inferior -->
                                <div class="input-group">
                                    <input type="color" class="form-control form-control-lg" id="color-secundario" name="color-secundario" value="{{ config('SCITS.color-secundario') }}" required>
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" id="toggle-color-secundario" type="button"><i class="bi bi-paint-bucket" style="font-size: 1.5rem;"></i></button>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group" id="color-complemento-group">
                                <label for="color-complemento" class="mb-2">Color complementario:</label> <!-- Agregamos margen inferior -->
                                <div class="input-group">
                                    <input type="color" class="form-control form-control-lg" id="color-complemento" name="color-complemento" value="{{ config('SCITS.color-complemento') }}" required>
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" id="toggle-color-complemento" type="button"><i class="bi bi-paint-bucket" style="font-size: 1.5rem;"></i></button>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group" id="color-fuente-p-group">
                                <label for="colorFuenteB" class="mb-2">Color de Fuente Primario:</label> <!-- Agregamos margen inferior -->
                                <div class="input-group">
                                    <input type="color" class="form-control form-control-lg" id="colorFuenteB" name="colorFuenteB" value="{{ config('SCITS.colorFuenteB') }}" required>
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" id="color-fuente-p" type="button"><i class="bi bi-paint-bucket" style="font-size: 1.5rem;"></i></button>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group" id="color-fuente-s-group">
                                <label for="colorFuenteN" class="mb-2">Color de Fuente Secundario:</label> <!-- Agregamos margen inferior -->
                                <div class="input-group">
                                    <input type="color" class="form-control form-control-lg" id="colorFuenteN" name="colorFuenteN" value="{{ config('SCITS.colorFuenteN') }}" required>
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" id="color-fuente-s" type="button"><i class="bi bi-paint-bucket" style="font-size: 1.5rem;"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mt-4">
                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary mr-2">Guardar Cambios</button> <!-- Agregamos margen a la derecha -->
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    // Función para activar el campo de entrada de color cuando se hace clic en el botón
    function activarCampoInput(idCampoInput) {
        document.getElementById(idCampoInput).click(); // Activa el campo de entrada de color
    }

    // Agrega un evento de clic a cada botón
    document.getElementById('toggle-color-primario').addEventListener('click', function() {
        activarCampoInput('color-primario');
    });
    document.getElementById('toggle-color-secundario').addEventListener('click', function() {
        activarCampoInput('color-secundario');
    });
    document.getElementById('toggle-color3').addEventListener('click', function() {
        activarCampoInput('color3');
    });
    document.getElementById('color-fuente-p').addEventListener('click', function() {
        activarCampoInput('colorFuenteP');
    });
    document.getElementById('color-fuente-s').addEventListener('click', function() {
        activarCampoInput('colorFuenteS');
    });
</script>
<script>
    function confirmarReinicioAnio() {
        if (confirm("¿Estás seguro de que quieres cambiar de año?")) {
            document.getElementById("form-reiniciar-anio").submit();
        }
    }
</script>

@endsection