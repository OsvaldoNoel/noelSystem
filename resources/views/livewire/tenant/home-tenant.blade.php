<div>

    <div>{{ session('user_type') }}</div>
    --------------------
    Datos de la empresa
    <div>{{ session('tenant') }}</div>
    <div>{{ session('tenant_id') }}</div>
    --------------------
    Datos de la sucursal si existe
    <div>{{ session('sucursal') }}</div>
    <div>{{ session('sucursal_id') }}</div>

    --------------------
    @if (session('user_permissions'))
        <div class="permissions-list">
            <h6>Permisos del Usuario</h6>
            <ul class="list-group">
                @foreach (session('user_permissions') as $permission)
                    <li class="list-group-item">{{ $permission }}</li>
                @endforeach
            </ul>
        </div>
    @else
        <div class="alert alert-info">No hay permisos asignados</div>
    @endif


</div>
