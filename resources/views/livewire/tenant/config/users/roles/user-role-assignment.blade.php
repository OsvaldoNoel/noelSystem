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
                                        <div class="text-muted small">CI: {{ $user['ci'] }}</div>
                                    </div>
                                    <div>
                                        @if ($user['is_owner'])
                                            <span class="badge bg-purple text-black">Propietario</span>
                                        @elseif($user['is_admin'])
                                            <span class="badge bg-info text-black">Admin</span>
                                        @elseif($user['roles_count'] > 0)
                                            <span class="badge bg-primary text-black">
                                                {{ $user['roles_count'] }} rol(es)
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <!-- Panel derecho - Roles y permisos -->
        <div class="col-md-8">
            @if ($selectedUser)
                <div class="card h-100 position-relative border-0">
                    <div class="position-absolute top-0 start-0 w-100 h-100 rounded-4 border border-3 border-black"
                        style="pointer-events: none;"></div>

                    <div class="card-header rounded-top-4 d-flex justify-content-between align-items-center">
                        <h4 class="ms-4 mt-3">
                            <span class="text-white">ROLES ASIGNADOS</span>
                            (<span class="text-yellow">**</span>
                            <span class="fs-6">Predefinidos del Sistema)</span>
                        </h4>
                    </div>
                    @if (!$selectedUser->hasRole('Propietario'))
                        <div class="card-body rounded-bottom-4">
                            <div class="mb-4">
                                <div class="row">
                                    <div class="col-2 mb-3">
                                        @foreach ($availableRoles as $role)
                                            <div class="form-check mb-3"
                                                wire:key="role-checkbox-{{ $role['id'] }}-{{ md5(serialize($userRoles)) }}">
                                                <input class="form-check-input" type="checkbox"
                                                    id="role-{{ $role['id'] }}" wire:model.live="userRoles"
                                                    wire:change="updateUserRoles('{{ $role['name'] }}', $event.target.checked)"
                                                    value="{{ $role['name'] }}"
                                                    @if ($this->shouldDisableCheckbox($role, $selectedUser)) disabled @endif
                                                    @checked(in_array($role['name'], $userRoles))>
                                                <label class="form-check-label d-flex align-items-center"
                                                    for="role-{{ $role['id'] }}">
                                                    {{ $role['name'] }}
                                                    @if ($role['is_predefined'])
                                                        <span class="text-yellow">**</span>
                                                    @endif
                                                    @if ($this->shouldShowLockIcon($role, $selectedUser) && $this->shouldDisableCheckbox($role, $selectedUser))
                                                        <span class="text-danger ms-1"
                                                            title="Restricciones de asignación">
                                                            <i class="fas fa-lock"></i>
                                                        </span>
                                                    @endif
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="col-10 mb-3">
                                        <div class="table-responsive" style="overflow-x: visible;">
                                            <!-- Cambiado a visible -->
                                            <table class="table table-sm table-bordered">
                                                <thead class="bg-black border-black">
                                                    <tr>
                                                        <th width="20%">Módulo</th>
                                                        <th>Permisos asignados</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($selectedUser->getGroupedPermissions() as $module => $permissions)
                                                        <tr>
                                                            <td class="align-middle"><!-- Añadido align-middle -->
                                                                <strong>{{ $module }}</strong>
                                                            </td>
                                                            <td>
                                                                @php
                                                                    // Convertir a array si es necesario (para manejar tanto arrays como colecciones)
                                                                    $permissionsArray = is_array($permissions)
                                                                        ? $permissions
                                                                        : $permissions->toArray();
                                                                    $chunks = array_chunk($permissionsArray, 4);
                                                                @endphp

                                                                @foreach ($chunks as $chunk)
                                                                    <div class="row g-1 mb-1">
                                                                        @php
                                                                            $chunk = array_pad($chunk, 4, null);
                                                                        @endphp

                                                                        @foreach ($chunk as $permission)
                                                                            <div class="col-3">
                                                                                @if ($permission)
                                                                                    @php
                                                                                        $parts = explode(
                                                                                            '-',
                                                                                            $permission['name'] ??
                                                                                                $permission->name,
                                                                                        );
                                                                                        $action = $parts[0];
                                                                                        $resource = implode(
                                                                                            ' ',
                                                                                            array_slice($parts, 1),
                                                                                        );
                                                                                    @endphp
                                                                                    <span
                                                                                        class="badge bg-success text-black d-block text-truncate"
                                                                                        title="{{ $permission['name'] ?? $permission->name }}">
                                                                                        {{ ucfirst($action) }}
                                                                                        {{ ucfirst($resource) }}
                                                                                    </span>
                                                                                @endif
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                @endforeach
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="card-body h-100 text-center align-content-center">
                            <i class="fas fa-user-shield fa-7x text-teal mb-3"></i>
                            <div class="alert alert-success me-5 ms-5 mt-3">
                                <i class="fas fa-info-circle me-2"></i>
                                El usuario Propietario tiene todos los permisos disponibles en el sistema.
                            </div>
                        </div>
                    @endif
                </div>
            @else
                <div class="card h-100 d-flex position-relative border-0">
                    <div class="position-absolute top-0 start-0 w-100 h-100 rounded-4 border border-3 border-black"
                        style="pointer-events: none;"></div>

                    <div class="card-body rounded-4 text-center align-content-center">
                        <i class="fas fa-user-circle fa-7x text-muted mb-3"></i>
                        <h5>Selecciona un usuario</h5>
                        <p class="text-muted">Haz clic en un usuario de la lista para ver y editar sus roles</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>



<script>
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('refresh-checkboxes', () => {
            // Forzar actualización de los checkboxes
            console.log('Refreshing checkboxes...');
            const checkboxes = document.querySelectorAll('.form-check-input');
            checkboxes.forEach(checkbox => {
                checkbox.checked = checkbox.hasAttribute('checked');
            });
        });
    });
</script>
