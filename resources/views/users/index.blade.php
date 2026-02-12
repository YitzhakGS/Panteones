@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box mb-0 d-flex align-items-center justify-content-between">
            <h4 class="page-title mb-0 font-size-18">
            <i class="bi bi-people"></i> Usuarios
            </h4>
        </div>
    </div>
</div>

    <button type="button" class="btn bg-base text-white" data-bs-toggle="modal" data-bs-target="#addUserModal">
    <i class="bi bi-plus-circle"></i>
</button>
@include('users.create')
<div class="card">

   
<table class="table table-bordered table-hover">
<thead class="table-dark">
            <tr>
            <th scope="col">ID</th>
                <th scope="col">Name</th>
                <th scope="col">Email</th>
                <th scope="col">sexo</th>
                <th scope="col">rol</th>
                <th scope="col">Roles</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $user)
            <tr>
                <th scope="row">{{ $loop->iteration }}</th>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->sexo }}</td>
                <td>{{ $user->rols->name ?? 'Sin Rol'}}</td>
                <td>
                    @forelse ($user->getRoleNames() as $role)
                    <span class="badge bg-primary">{{ $role }}</span>
                    @empty
                    @endforelse
                </td>
                <td>
                    <form action="{{ route('users.destroy', $user->id) }}" method="post">
                        @csrf
                        @method('DELETE')

                        
                        <button type="button" class="btn bg-base text-white " data-bs-toggle="modal" data-bs-target="#userInfoModal"><i class="bi bi-eye"></i></button>
                        @include('users.show')
                        @if (in_array('Super Admin', $user->getRoleNames()->toArray() ?? []) )
                        @if (Auth::user()->hasRole('Super Admin'))
                        <!-- Button to open the modal -->
<a href="#" class="btn bg-base text-white" data-bs-toggle="modal" data-bs-target="#userEditModal">
    <i class="bi bi-pencil-square"></i> 
</a>

                       
                        @endif

                        @else
                        @can('edit-user')
<!-- Button to open the modal -->
<a href="#" class="btn bg-base text-white" data-bs-toggle="modal" data-bs-target="#userEditModal">
    <i class="bi bi-pencil-square"></i> 
</a>

                      
                        
                        @endcan

                        @can('delete-user')
                        @if (Auth::user()->id!=$user->id)
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Do you want to delete this user?');"><i class="bi bi-trash"></i> </button>
                        @endif
                        @endcan
                        @endif

                    </form>
                </td>
            </tr>
            @empty
            <td colspan="5">
                <span class="text-danger">
                    <strong>No User Found!</strong>
                </span>
            </td>
            @endforelse
        </tbody>
    </table>

    {{ $users->links() }}

</div>

@include('users.edit')
@endsection