<!-- resources/views/userModal.blade.php -->

<div class="modal fade" id="userInfoModal" tabindex="-1" aria-labelledby="userInfoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userInfoModalLabel">User Information</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Display user info inside modal -->
                <div class="mb-3 row">
                    <label for="name" class="col-md-4 col-form-label text-md-end text-start"><strong>Name:</strong></label>
                    <div class="col-md-6" style="line-height: 35px;">
                        {{ $user->name }}
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="email" class="col-md-4 col-form-label text-md-end text-start"><strong>Email Address:</strong></label>
                    <div class="col-md-6" style="line-height: 35px;">
                        {{ $user->email }}
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="sexo" class="col-md-4 col-form-label text-md-end text-start"><strong>Sexo:</strong></label>
                    <div class="col-md-6" style="line-height: 35px;">
                        {{ $user->sexo }}
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="rol" class="col-md-4 col-form-label text-md-end text-start"><strong>Rol:</strong></label>
                    <div class="col-md-6" style="line-height: 35px;">
                        {{ $user->rol }}
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="roles" class="col-md-4 col-form-label text-md-end text-start"><strong>Roles:</strong></label>
                    <div class="col-md-6" style="line-height: 35px;">
                        @forelse ($user->getRoleNames() as $role)
                            <span class="badge bg-primary">{{ $role }}</span>
                        @empty
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
