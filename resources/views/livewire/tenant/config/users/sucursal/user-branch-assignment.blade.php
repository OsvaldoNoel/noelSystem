<div>
    <div class="row">
        <!-- Panel izquierdo - Lista de usuarios -->
        <div class="col-md-4">
            <div class="card h-100 d-flex position-relative border-0">
                <div class="position-absolute top-0 start-0 w-100 h-100 rounded-4 border border-3 border-black"
                    style="pointer-events: none;"></div>

                <div class="card-header d-flex justify-content-between align-items-center rounded-top-4">
                    <h5 class="ms-5 mt-3 card-title">USUARIOS</h5>
                    <div class="input-group mb-2 mt-2" style="width: 200px;">
                        <input type="text" wire:model.live.debounce.300ms="search"
                            class="form-control form-control-sm" placeholder="Buscar...">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                    </div>
                </div>
                <div class="card-body rounded-bottom-4 p-0">
                    <ul class="list-group list-group-flush m-3">
                        @foreach ($users as $user)
                            <li class="list-group-item @if ($selectedUser && $selectedUser->id == $user['id']) bg-success-subtle @endif"
                                wire:click="selectUser({{ $user['id'] }})" style="cursor: pointer">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $user['name'] }} {{ $user['lastname'] }}</strong>
                                        <div class="text-muted small">
                                            CI: {{ $user['ci'] }}
                                            @if ($user['is_owner'])
                                                <span class="badge bg-purple text-black">Propietario</span>
                                            @elseif($user['is_admin'])
                                                <span class="badge bg-teal text-black">Admin</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <div class="mt-1">
                                            <span class="badge bg-info text-black">
                                                {{ $user['branch_name'] }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <!-- Panel derecho - Asignación de sucursal -->
        <div class="col-md-8">
            @if ($selectedUser)
                <div class="card h-100 position-relative border-0">
                    <div class="position-absolute top-0 start-0 w-100 h-100 rounded-4 border border-3 border-black"
                        style="pointer-events: none;"></div>

                    <div class="card-header rounded-top-4 d-flex justify-content-between align-items-center">
                        <h4 class="ms-4 mt-3">
                            <span class="text-white">Seleccione sucursal:</span>
                        </h4>
                    </div>
                    <div class="card-body h-100 text-center align-content-center rounded-bottom-4">
                        <div class="mb-4">
                            <div class="row">
                                <div class="col-12">
                                    @if ($selectedUser->hasRole(['Propietario', 'Admin']))

                                        <div class="position-relative"
                                            style="width: 100px; height: 100px; margin: 0 auto;">
                                            <!-- Casa más grande (fa-7x) -->
                                            <i class="fas fa-home fa-7x text-info opacity-25"></i>
                                            <!-- Usuario desplazado a la derecha (60% en lugar de 50%) -->
                                            <i class="fas fa-user fa-3x text-info position-absolute"
                                                style="top: 70%; left: 10%; transform: translate(-50%, -50%);"></i>
                                        </div>

                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle me-2"></i>
                                            Los usuarios con rol
                                            <strong>{{ $selectedUser->hasRole('Propietario') ? 'Propietario' : 'Admin' }}</strong>
                                            siempre pertenecen a la Casa Central.
                                        </div>
                                    @else
                                        <div class="row g-2 mt-3">
                                            @foreach ($availableBranches as $branch)
                                                <div class="col-6 col-md-4">
                                                    <input type="radio" class="btn-check" name="branchSelection"
                                                        id="branch_{{ $branch['id'] ?? 'null' }}"
                                                        wire:model="selectedBranch" value="{{ $branch['id'] ?? '' }}"
                                                        wire:click="updateUserBranch" autocomplete="off"
                                                        @if ($selectedUser->hasRole(['Propietario', 'Admin'])) disabled @endif
                                                        @if (($branch['id'] ?? '') === ($selectedBranch ?? '')) checked @endif>
                                                    <label
                                                        class="btn btn-outline-info w-100 py-3 d-flex flex-column align-items-center"
                                                        for="branch_{{ $branch['id'] ?? 'null' }}">
                                                        @if (is_null($branch['id']))
                                                            <i class="fas fa-home fa-2x mb-2"></i>
                                                        @else
                                                            <i class="fas fa-store fa-2x mb-2"></i>
                                                        @endif
                                                        {{ $branch['name'] }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="card h-100 d-flex position-relative border-0">
                    <div class="position-absolute top-0 start-0 w-100 h-100 rounded-4 border border-3 border-black"
                        style="pointer-events: none;"></div>

                    <div class="card-body rounded-4 text-center align-content-center">
                        <i class="fas fa-user-circle fa-7x text-muted mb-3"></i>
                        <h5>Selecciona un usuario</h5>
                        <p class="text-muted">Haz clic en un usuario de la lista para asignarle una sucursal</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
