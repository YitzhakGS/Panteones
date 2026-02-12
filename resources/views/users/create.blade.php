<!-- resources/views/modals/add_user.blade.php -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUserModalLabel">Add New User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('users.store') }}" method="post">
                    @csrf
                    <div class="mb-3 row">
                        <label for="name" class="col-md-4 col-form-label text-md-end text-start">Name</label>
                        <div class="col-md-6">
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}">
                            @if ($errors->has('name'))
                            <span class="text-danger">{{ $errors->first('name') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="email" class="col-md-4 col-form-label text-md-end text-start">Email Address</label>
                        <div class="col-md-6">
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}">
                            @if ($errors->has('email'))
                            <span class="text-danger">{{ $errors->first('email') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="password" class="col-md-4 col-form-label text-md-end text-start">Password</label>
                        <div class="col-md-6">
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
                            @if ($errors->has('password'))
                            <span class="text-danger">{{ $errors->first('password') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="password_confirmation" class="col-md-4 col-form-label text-md-end text-start">Confirm Password</label>
                        <div class="col-md-6">
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="sexo" class="col-md-4 col-form-label text-md-end text-start">Sexo</label>
                        <div class="col-md-6">
                            <select class="form-control @error('sexo') is-invalid @enderror" id="sexo" name="sexo" onchange="toggleOtroInput()">
                                <option value="">Seleccione una opción</option>
                                <option value="Hombre" {{ old('sexo') == 'Hombre' ? 'selected' : '' }}>Hombre</option>
                                <option value="Mujer" {{ old('sexo') == 'Mujer' ? 'selected' : '' }}>Mujer</option>
                                <option value="otro" {{ old('sexo') == 'otro' ? 'selected' : '' }}>Otro</option>
                            </select>
                            @if ($errors->has('sexo'))
                            <span class="text-danger">{{ $errors->first('sexo') }}</span>
                            @endif
                        </div>
                    </div>

                    <!-- Input oculto para "Otro" -->
                    <div class="mb-3 row" id="otroSexoContainer" style="display: none;">
                        <label for="otro_sexo" class="col-md-4 col-form-label text-md-end text-start">Especifique su género</label>
                        <div class="col-md-6">
                            <input type="text" class="form-control @error('otro_sexo') is-invalid @enderror" id="otro_sexo" name="otro_sexo" value="{{ old('otro_sexo') }}">
                            @if ($errors->has('otro_sexo'))
                            <span class="text-danger">{{ $errors->first('otro_sexo') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="rol" class="col-md-4 col-form-label text-md-end text-start">Rol</label>
                        <div class="col-md-6">
                            <select class="form-control @error('rol') is-invalid @enderror" id="rol" name="rol">
                                <option value="">Seleccione un rol</option>
                                @foreach ($rols as $id => $name)
                                <option value="{{ $id }}" {{ old('rol') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('rol'))
                            <span class="text-danger">{{ $errors->first('rol') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="roles" class="col-md-4 col-form-label text-md-end text-start">Roles</label>
                        <div class="col-md-6">
                            <select class="form-select @error('roles') is-invalid @enderror" multiple aria-label="Roles" id="roles" name="roles[]">
                                @foreach ($roles as $role)
                                @if ($role != 'Super Admin')
                                <option value="{{ $role }}" {{ in_array($role, old('roles') ?? []) ? 'selected' : '' }}>{{ $role }}</option>
                                @else
                                @if (Auth::user()->hasRole('Super Admin'))
                                <option value="{{ $role }}" {{ in_array($role, old('roles') ?? []) ? 'selected' : '' }}>{{ $role }}</option>
                                @endif
                                @endif
                                @endforeach
                            </select>
                            @if ($errors->has('roles'))
                            <span class="text-danger">{{ $errors->first('roles') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <input type="submit" class="btn bg-base text-white" value="Add User">
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
<script>
    function toggleOtroInput() {
        var selectSexo = document.getElementById('sexo');
        var otroSexoContainer = document.getElementById('otroSexoContainer');
        var otroSexoInput = document.getElementById('otro_sexo');

        if (selectSexo.value === 'otro') {
            otroSexoContainer.style.display = 'block';
            otroSexoInput.required = true;
        } else {
            otroSexoContainer.style.display = 'none';
            otroSexoInput.required = false;
            otroSexoInput.value = '';
        }
    }

    window.onload = function() {
        toggleOtroInput();
    };
</script>
