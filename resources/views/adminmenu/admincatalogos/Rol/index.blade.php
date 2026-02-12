@extends('layouts.app')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box mb-0 d-flex align-items-center justify-content-between">
            <h4 class="page-title mb-0 font-size-18">
            <i class="bi bi-sliders2"></i> Rol
            </h4>
        </div>
    </div>
</div>
<div class="container">
    

    <!-- Botón Agregar -->
    <button class="btn bg-base text-white" data-toggle="modal" data-target="#addModal">
    <i class="bi bi-plus-circle"></i>
    </button>



    <table class="table table-bordered table-hover">
    <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($roles as $rol)
            <tr>
                <td>{{ $rol->id }}</td>
                <td>{{ $rol->name }}</td>
                <td>
                <button class="btn bg-base text-white" data-toggle="modal" data-target="#editModal{{ $rol->id }}">
                <i class="bi bi-pencil"></i> <!-- Ícono de lápiz para editar -->
                </button>

                    <form action="{{ route('rol.destroy', $rol->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger " onclick="return confirm('¿Estás seguro?')">
                        <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal de Crear -->
<div class="modal fade" id="createModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Agregar Nuevo Rol</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="createForm" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="newRoleName">Nombre del Rol</label>
                        <input type="text" class="form-control" id="newRoleName" name="name" required>
                    </div>
                    <button type="submit" class="btn btn-success">Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>

@foreach ($roles as $rol)
    <!-- Modal para editar rol -->
    <div class="modal fade" id="editModal{{ $rol->id }}" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Rol</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('rol.update', $rol->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="editRoleName">Nombre del Rol</label>
                            <input type="text" class="form-control" name="name" value="{{ $rol->name }}" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar cambios</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach



<!-- Modal para agregar un nuevo rol -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Agregar Nuevo Rol</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form action="{{ route('rol.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="newRoleName">Nombre del Rol</label>
                        <input type="text" class="form-control" id="newRoleName" name="name" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>




@endsection
