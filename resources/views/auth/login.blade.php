<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <title>NoelSystem | Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="Noel System" />
    <meta name="author" content="Noel" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- ================== BEGIN core-css ================== -->
    <link href="{{ asset('assets/css/vendor.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/appTenant.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/login.css') }}" rel="stylesheet" />
    <!-- ================== END core-css ================== -->
</head>

<body>
    <div id="app" class="app app-full-height app-without-header">
        <div class="login">
            <div class="login-content">
                <form method="POST" action="{{ route('login') }}" id="loginForm">
                    @csrf
                    <!-- Campos ocultos para los datos requeridos -->
                    <input type="hidden" id="username" name="username" value="">
                    <input type="hidden" id="ci_field" name="ci" value="">

                    <h1 class="text-center">Acceso</h1>
                    <div class="text-body text-opacity-50 text-center mb-5">
                        Por seguridad, favor verifique su identidad...
                    </div>

                    <!-- Paso 1: Ingreso de CI -->
                    <div class="mb-4" id="ciStep">
                        <label class="form-label" for="ci_input">Número de Cédula</label>
                        <input id="ci_input" type="text" class="form-control form-control-lg fs-14px" required
                            autocomplete="off" />
                        <div id="ciError" class="error text-danger mt-1 d-none"></div>
                    </div>

                    <!-- Paso 2: Selección de Tenant -->
                    <div class="mb-4 d-none" id="tenantStep">
                        <label class="form-label" for="tenant_id">Seleccione su Empresa</label>
                        <select id="tenant_id" class="form-select form-select-lg fs-14px" required>
                            <!-- Se llenará dinámicamente con: -->
                            <!-- <option value="" selected disabled>-- Seleccione Sucursal --</option> -->
                            <!-- <option value="landlord">🔑 Landlord</option> -->
                            <!-- <option value="1">🏢 Sucursal Central</option> -->
                        </select>
                    </div>

                    <!-- Paso 3: Credenciales -->
                    <div class="d-none" id="credentialsStep">
                        <div class="mb-4">
                            <label for="password" class="form-label">Contraseña</label>
                            <input id="password" name="password" type="password"
                                class="form-control form-control-lg fs-14px" required autocomplete="current-password" />
                            <div id="passwordError" class="error text-danger mt-1 d-none"></div>
                        </div>
                        <button type="submit" class="btn btn-theme btn-lg d-block w-100 mb-3">INGRESAR</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ================== BEGIN core-js ================== -->
    <script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js"></script>
    <script src="{{ asset('assets/js/vendor.min.js') }}"></script>
    <script src="{{ asset('assets/js/app.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- ================== END core-js ================== -->

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ciInput = document.getElementById('ci_input');
            const tenantSelect = document.getElementById('tenant_id');
            const ciStep = document.getElementById('ciStep');
            const tenantStep = document.getElementById('tenantStep');
            const credentialsStep = document.getElementById('credentialsStep');
            const ciError = document.getElementById('ciError');
            const usernameField = document.getElementById('username');
            const ciField = document.getElementById('ci_field');
            const passwordInput = document.getElementById('password');
            const passwordError = document.getElementById('passwordError');
            const loginForm = document.getElementById('loginForm');

            // Función para resetear completamente el estado
            function resetValidation() {
                ciError.classList.add('d-none');
                tenantStep.classList.add('d-none');
                credentialsStep.classList.add('d-none');
                tenantSelect.innerHTML = '<option value="" selected disabled>-- Seleccione Sucursal --</option>';
                usernameField.value = '';
                ciField.value = '';
            }

            // Función de validación
            async function validateCi() {
                const currentCi = ciInput.value.trim();

                // Validación mínima de longitud
                if (currentCi.length < 3) {
                    resetValidation();
                    if (currentCi.length > 0) {
                        showError('El CI debe tener al menos 3 caracteres');
                    }
                    return;
                }

                try {
                    const response = await fetch("{{ route('validate.ci') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            ci: currentCi
                        })
                    });

                    const data = await response.json();

                    if (data.success) {
                        ciError.classList.add('d-none');
                        populateTenants(data.tenants);
                        ciStep.classList.add('mb-4');
                        tenantStep.classList.remove('d-none');
                        ciField.value = currentCi;

                        // Enfocar el select de tenant después de 100ms (para asegurar que esté visible)
                        setTimeout(() => {
                            tenantSelect.focus();
                        }, 100);
                    } else {
                        resetValidation();
                        showError(data.message);
                    }
                } catch (error) {
                    resetValidation();
                    showError('Error de conexión');
                }
            }

            // Eventos del campo CI
            ciInput.addEventListener('input', resetValidation);
            ciInput.addEventListener('blur', validateCi);
            ciInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    validateCi();
                }
            });

            // Evento de selección de tenant
            tenantSelect.addEventListener('change', function() {
                if (this.value && this.value !== "") {
                    usernameField.value = this.value === 'landlord' ?
                        ciField.value :
                        ciField.value + this.value;
                    credentialsStep.classList.remove('d-none');

                    // Enfocar el campo de contraseña después de 100ms
                    setTimeout(() => {
                        passwordInput.focus();
                    }, 100);
                } else {
                    credentialsStep.classList.add('d-none');
                }
            });

            function populateTenants(tenants) {
                tenantSelect.innerHTML = '<option class="d-none" value="" selected disabled>-- Seleccione --</option>';
                tenants.forEach(tenant => {
                    const option = document.createElement('option');
                    option.value = tenant.id;
                    option.textContent = tenant.name;
                    tenantSelect.appendChild(option);
                });
            }

            function showError(message) {
                ciError.textContent = message;
                ciError.classList.remove('d-none');
                ciInput.focus();
            }

            // Evento submit del formulario
            loginForm.addEventListener('submit', async function(e) {
                e.preventDefault();

                // Limpiar errores previos
                passwordError.classList.add('d-none');
                ciError.classList.add('d-none');

                try {
                    const response = await fetch("{{ route('login') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .content
                        },
                        body: JSON.stringify({
                            username: usernameField.value,
                            password: passwordInput.value,
                            ci: ciField.value,
                            tenant_id: tenantSelect.value === 'landlord' ? null :
                                tenantSelect.value,
                            remember: document.querySelector('input[name="remember"]')
                                ?.checked || false
                        })
                    });

                    const data = await response.json();

                    if (!response.ok) {
                        // Manejar errores de validación
                        if (data.errors?.password) {
                            passwordError.textContent = data.errors.password[0];
                            passwordError.classList.remove('d-none');
                            passwordInput.value = '';
                            passwordInput.focus();
                        }
                        if (data.errors?.username) {
                            ciError.textContent = data.errors.username[0];
                            ciError.classList.remove('d-none');
                            resetValidation();
                        }
                        return;
                    }

                    // Redirección en caso de éxito
                    window.location.href = data.redirect;

                } catch (error) {
                    passwordError.textContent = 'Contraseña incorrecta';
                    passwordError.classList.remove('d-none');
                    passwordInput.focus();
                }
            });
        });
    </script>
</body>

</html>
