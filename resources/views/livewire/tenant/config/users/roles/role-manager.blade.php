<div>
    <div class="row">
        <!-- Panel izquierdo - Lista de roles -->
        <div class="col-md-4">
            <div class="card h-100 position-relative border-0">
                <div class="position-absolute top-0 start-0 w-100 h-100 rounded-4 border border-3 border-black"
                    style="pointer-events: none;"></div>

                <div class="card-header rounded-top-4">
                    <div class="input-group mb-2 mt-2">
                        <input type="text" wire:model="newRoleName" class="form-control" placeholder="Nuevo rol">
                        <button wire:click="createRole" class="btn btn-outline-success">
                            <i class="fas fa-plus"></i> Crear Rol
                        </button>
                    </div>
                </div>
                <div class="card-body rounded-bottom-4">
                    <ul class="list-group">
                        <!-- Roles del Sistema -->
                        <li class="list-group-item bg-gray-900 border-black">
                            <strong class="ms-4 text-info">Roles del Sistema</strong>
                        </li>
                        @foreach ($roles as $role)
                            @if ($role['is_predefined'])
                                <li class="list-group-item @if ($selectedRole && $selectedRole->id == $role['id']) bg-success-subtle @endif"
                                    wire:click="selectRole({{ $role['id'] }})" style="cursor: pointer">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span>{{ $role['name'] }}</span>
                                        <div>
                                            <span class="badge bg-primary text-black fw-bold rounded-pill">
                                                {{ $role['permissions_count'] }}
                                            </span>
                                        </div>
                                    </div>
                                </li>
                            @endif
                        @endforeach

                        <!-- Roles Personalizados - Solo mostrar si hay roles personalizados -->
                        @if (count(array_filter($roles, function ($role) {
                                    return !$role['is_predefined'];
                                })) > 0)
                            <li class="list-group-item bg-gray-900 border-black mt-3">
                                <strong class="ms-4 text-info">Roles Personalizados</strong>
                            </li>
                            @foreach ($roles as $role)
                                @if (!$role['is_predefined'])
                                    <li
                                        class="list-group-item @if ($selectedRole && $selectedRole->id == $role['id']) bg-success-subtle @endif p-0">
                                        <div class="d-flex justify-content-between align-items-center w-100">
                                            @if ($editingRoleId == $role['id'])
                                                <!-- Modo edición -->
                                                <div class="d-flex align-items-center flex-grow-1 py-2 px-3">
                                                    <input type="text" wire:model="editedRoleName"
                                                        @keydown.enter="updateRoleName" @keydown.escape="cancelEditing"
                                                        class="form-control form-control-sm me-2">
                                                    <button wire:click="updateRoleName"
                                                        class="btn btn-sm btn-success me-1" title="Guardar cambios">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                    <button wire:click="cancelEditing" class="btn btn-sm btn-secondary"
                                                        title="Cancelar edición">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                            @else
                                                <!-- Modo visualización -->
                                                <div wire:click="selectRole({{ $role['id'] }})"
                                                    class="d-flex justify-content-between align-items-center flex-grow-1 py-2 px-3"
                                                    style="cursor: pointer">
                                                    <span>{{ $role['name'] }}</span>
                                                    <span class="badge bg-primary text-black fw-bold rounded-pill ms-2">
                                                        {{ $role['permissions_count'] }}
                                                    </span>
                                                </div>
                                            @endif

                                            <!-- Botones de acción -->
                                            <div class="d-flex">
                                                @if ($editingRoleId != $role['id'])
                                                    <button wire:click.stop="startEditing({{ $role['id'] }})"
                                                        class="btn btn-outline-primary btn-sm m-1 rounded"
                                                        title="Editar nombre del rol">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                @endif
                                                <button wire:click.stop="deleteRole({{ $role['id'] }})"
                                                    class="btn btn-outline-danger btn-sm m-1 rounded"
                                                    title="Eliminar rol">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </li>
                                @endif
                            @endforeach
                        @endif
                    </ul>
                </div>
            </div>
        </div>

        <!-- Panel derecho - Permisos -->
        <div class="col-md-8">
            @if ($selectedRole)
                <div class="card h-100 position-relative border-0">
                    <div class="position-absolute top-0 start-0 w-100 h-100 rounded-4 border border-3 border-black"
                        style="pointer-events: none;"></div>

                    <div class="card-header rounded-top-4 d-flex justify-content-between align-items-center">
                        <h5 class="card-title m-3">Permisos para: {{ $selectedRole->name }}</h5>
                    </div>
                    <div class="card-body rounded-bottom-4 p-0">
                        <div class="permissions-container">
                            <table class="table table-borderless mb-0">
                                @foreach ($this->chunkedPermissions as $permissionPair)
                                    <tbody class="border-top">
                                        <tr>
                                            @foreach ($permissionPair as $modulePermissions)
                                                @foreach ($modulePermissions as $module => $permissions)
                                                    <td class="p-3" style="width: 50%; vertical-align: top;">
                                                        <div
                                                            class="d-flex justify-content-between align-items-center mb-2">
                                                            <strong>{{ $module }}</strong>
                                                        </div>

                                                        @foreach ($permissions as $permission)
                                                            <div class="form-check mb-2 ms-2">
                                                                <input class="form-check-input" type="checkbox"
                                                                    wire:model="rolePermissions"
                                                                    wire:change="updateRolePermissions"
                                                                    value="{{ $permission }}"
                                                                    id="perm-{{ $permission }}"
                                                                    @if (is_null($selectedRole->tenant_id)) disabled @endif
                                                                    style="@if (is_null($selectedRole->tenant_id)) opacity: 0.5; @endif">
                                                                <label class="form-check-label"
                                                                    for="perm-{{ $permission }}">
                                                                    {{ Str::title(str_replace('-', ' ', $permission)) }}
                                                                </label>
                                                            </div>
                                                        @endforeach
                                                    </td>
                                                @endforeach
                                            @endforeach
                                        </tr>
                                    </tbody>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>
            @else
                <div class="card h-100 d-flex position-relative border-0">
                    <div class="position-absolute top-0 start-0 w-100 h-100 rounded-4 border border-3 border-black"
                        style="pointer-events: none;"></div>

                    <div class="card-body rounded-4 text-center align-content-center">
                        <i class="fas fa-user-shield fa-7x text-muted mb-3"></i>
                        <h4 class="mb-3">Selecciona un rol de la lista</h4>
                        <p class="text-muted mb-0">Tenga en cuenta que los permisos del sistema no se pueden modificar
                        </p>
                        <p class="text-muted">Puede crear un "Nuevo Rol" y administrar sus permisos</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@push('styles')
    <style>
        /* Estilos para el modo edición */
        .form-control-sm {
            height: calc(1.5em + 0.5rem + 2px);
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }

        /* Espaciado para botones */
        .btn-sm {
            padding: 0.25rem 0.5rem;
        }

        /* Tooltips */
        .tooltip {
            pointer-events: auto !important;
        }

        /* Mejor contraste para badges */
        .badge {
            font-weight: 500;
            min-width: 1.5rem;
            text-align: center;
        }
    </style>
@endpush
 