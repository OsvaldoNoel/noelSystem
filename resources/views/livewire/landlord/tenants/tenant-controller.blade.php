<div>
    @include('livewire.landlord.tenants.modals')
    <div id="tableContainer" wire:ignore>
        <!-- Input de búsqueda personalizado -->
        <div class="row mb-2 align-items-center"> <!-- align-items-center para centrado vertical -->
            <div class="col-md-6">
                <input type="text" class="form-control form-control-sm" wire:model.live.debounce.300ms="search"
                    placeholder="Buscar empresas...">
            </div>
            <div class="col-md-6 d-flex justify-content-end align-items-center"> <!-- Contenedor flex -->
                <button wire:click="$dispatch('openCreateModal')" class="btn btn-link pe-3">
                    <!-- Estilo más limpio para botón -->
                    <i class="fa-solid fa-square-plus fs-3 text-info"></i> <!-- Icono con tamaño y color -->
                </button>
            </div>
        </div>

        <table id="{{ $componentName }}" width="100%" class="table table-hover text-nowrap">
            <thead class="bg-gray-900">
                <tr>
                    <th class="text-theme">ACT</th>
                    <th class="text-theme">EMPRESA</th>
                    <th class="text-theme">TIPO</th> <!-- Nueva columna -->
                    <th class="text-theme">SUCURSAL</th>
                    <th></th>
                </tr>
            </thead>
        </table>
    </div>
</div>

@script
    <script>
        const TABLE_NAME = $wire.componentName;
        let table;
        let lastSearch = '';
        let lastPage = 0;
        let lastOrder = [
            [4, 'asc']
        ];
        let resizeTimeout;

        // Configuración de columnas
        function getColumnsConfig() {
            return [{
                    targets: 0,
                    sortable: false,
                    render: function(td, cellData, rowData, row, col) {
                        let checked = "";
                        if (rowData['status'] == 1) {
                            checked = "checked";
                        };
                        return '<div class="form-check form-switch"><input id="' +
                            rowData['name'] + rowData['id'] + '" type="checkbox" ' +
                            checked + '  class="form-check-input btnStatus"></div>';
                    },
                },
                {
                    targets: 1,
                    sortable: false,
                    render: function(td, cellData, rowData) {
                        // Mostramos todo en esta columna
                        if (rowData.sucursal) {
                            return `<span class="ms-5"> ↳ ${rowData.name}</span>`;
                        }
                        return `<strong class="strong">${rowData.name}</strong>`;
                    }
                },
                {
                    targets: 2, // Nueva columna para el tipo de tenant
                    sortable: false,
                    render: function(td, cellData, rowData) {
                        // Solo mostrar para empresas principales (no sucursales)
                        if (rowData.sucursal) {
                            return ''; // No mostrar nada para sucursales
                        }

                        // Definir los estilos según el tipo
                        const types = {
                            1: {
                                class: 'bg-teal',
                                text: 'POS'
                            },
                            2: {
                                class: 'bg-yellow',
                                text: 'Servicios'
                            },
                            3: {
                                class: 'bg-info',
                                text: 'MicroVentas'
                            },
                            4: {
                                class: 'bg-red',
                                text: 'Restaurante'
                            }
                        };

                        const type = rowData.tenant_type || 1; // Default a POS si no hay tipo
                        const typeInfo = types[type] || types[1];

                        return `<span class="badge ${typeInfo.class} bg-opacity-25 text-white py-4px px-2 fs-9px d-inline-flex align-items-center">
                    <i class="fa fa-circle opacity-5 fs-4px fa-fw me-2"></i> ${typeInfo.text}
                </span>`;
                    }
                },
                {
                    targets: 3,
                    visible: false, // Columna oculta
                    data: 'sucursal', // Solo para cálculos internos
                    render: function(td, cellData, rowData) {
                        return rowData.sucursal || '';
                    }
                },
                {
                    targets: 4,
                    sortable: false,
                    render: function() {
                        return '<div class="dropdown text-end"><a class="nav-link fs-5" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-ellipsis"></i></a>' +
                            '<ul class="dropdown-menu bg-black">' +
                            '<li><a class="dropdown-item text-theme btnEditar"><i class="bi-pencil-square"></i> Edit </a></li>' +
                            // '<li><a class="dropdown-item text-theme btnEliminar"><i class="bi-trash3-fill"></i> Delete </a></li>' +
                            '</ul>' +
                            '</div>';
                    },
                },
                // Columna 5: Orden (oculta)
                {
                    targets: 5,
                    data: 'sort_order',
                    visible: false
                },
                {
                    targets: 6,
                    visible: false,
                    data: 'is_main'
                }
            ];
        }

        // Configuración de idioma
        function getLanguageConfig() {
            return {
                info: 'Mostrando pág _PAGE_ de _PAGES_',
                infoEmpty: 'No se encontraron coincidencias',
                lengthMenu: 'Display _MENU_ records per page',
                infoFiltered: '(  _MAX_ registros en la tabla)',
                zeroRecords: "No se encontraron registros para la busqueda",
            };
        }

        // Configuración base de DataTable
        function getTableConfig(data) {
            return {
                data: data,
                order: [
                    [4, 'asc']
                ], // Ordenar por el campo sort_order 
                autoWidth: true,
                columns: getColumnsConfig(),
                dom: "<'row align-content-between'<'col-10 mb-0'f>>t<'d-lg-flex align-items-center mt-3' >",
                scrollY: '41vh',
                lengthMenu: [{
                    value: -1
                }],
                searching: false, // Deshabilitar búsqueda interna 
                paging: false,
                pageLength: 15,
                language: getLanguageConfig(),
                initComplete: function() {
                    if (lastSearch) this.api().search(lastSearch).draw();
                    if (lastPage > 0) this.api().page(lastPage).draw(false);
                }
            };
        }

        // Manejadores de eventos
        function setupEventHandlers() {
            // Guardar estado antes de acciones
            table.on('search.dt', () => lastSearch = table.search());
            table.on('page.dt', () => lastPage = table.page());
            table.on('order.dt', () => lastOrder = table.order());

            // Placeholder al input de búsqueda
            $('div.dt-search input[type="search"]')
                .attr('placeholder', 'Buscar...')
                .attr('autocomplete', 'off');
            $('div.dt-search label').hide();

            // Cambio de status 
            $(`#${TABLE_NAME} tbody`).on('click', '.btnStatus', function() {
                const rowData = table.row($(this).closest('tr')).data();
                if (!rowData) return; // Protección contra datos undefined

                // Actualizar el estado
                const newStatus = rowData.status == 0 ? 1 : 0;
                table.row($(this).closest('tr')).data({
                    ...rowData,
                    status: newStatus
                }).draw();

                // Enviar evento a Livewire
                $wire.dispatch(`status${TABLE_NAME}`, {
                    id: rowData.id
                });
            });

            // Manejador de edición - Versión final
            $(`#${TABLE_NAME} tbody`).on('click', '.btnEditar', function() {
                const row = table.row($(this).parents('tr')).data();

                // Actualizar solo el nombre
                $wire.set('selected_id', row.id);
                $wire.set('sucursal', row.sucursal);
                $wire.set('name', row.name);

                // Mostrar modal de edición
                $(`#editModal${TABLE_NAME}`).modal('show');
            });

            // Eliminar
            // $(`#${TABLE_NAME} tbody`).on('click', '.btnEliminar', function() {
            //     const row = table.row($(this).parents('tr')).data();
            //     window.Livewire.dispatch('swal:confirm', {
            //         background: 'info',
            //         html: `Eliminar <b><i>"${row.name}"</i></b> del registro?`,
            //         next: {
            //             event: `delete${TABLE_NAME}`,
            //             params: {
            //                 id: row.id
            //             }
            //         }
            //     });
            // });
        }

        // Función para cargar empresas principales (sucursal = null) 
        function cargarEmpresasPrincipales() {
            const select = $(`#addModal${TABLE_NAME} #selectEmpresasPrincipales`);
            select.empty().append(
                '<option value="" selected disabled class="d-none">-- Seleccione una empresa principal --</option>');

            // Filtrar solo empresas principales (sucursal = null)
            const empresasPrincipales = table.data().toArray().filter(t => !t.sucursal || t.sucursal === '-');
            $wire.tenantsList = empresasPrincipales;

            empresasPrincipales.forEach(empresa => {
                select.append(new Option(empresa.name, empresa.id));
            });
        }

        //variables de la modal
        const branchContainer = $(`#addModal${TABLE_NAME} .branch-select-container`);
        const leaderContainer = $(`#addModal${TABLE_NAME} .leader-select-container`);
        const ownerContainer = $(`#addModal${TABLE_NAME} .owner-select-container`);

        // Escuchar cambios en el select de empresas principales
        $(`#addModal${TABLE_NAME} #selectEmpresasPrincipales`).on('change', function() {
            const tenantId = $(this).val();
            if (tenantId) {
                $wire.set('sucursal', tenantId);
                $wire.dispatch('searchOwner', {
                    tenantId: tenantId
                });
            }
        });

        // Manejador de cambio de tipo en creación
        $(document).on('change', `#addModal${TABLE_NAME} input[name="tenant_type"]`, function() {
            const isBranch = $(this).val() === 'sucursal';

            $wire.is_branch = isBranch

            // Cargar empresas principales si es sucursal
            if (isBranch && $('#selectEmpresasPrincipales option').length <= 1) {
                cargarEmpresasPrincipales();
            }

            // Configuración de textos y visibilidad
            $('#labelTitleSelect').text(isBranch ? 'Nombre para la Sucursal' : 'Nombre para la nueva Empresa');
            $('#buttonAddTenant').text(isBranch ? 'Registrar Sucursal' : 'Registrar Empresa y Propietario');
            $(`#nameAdd${TABLE_NAME}`).focus();

            branchContainer.toggleClass('d-none', !isBranch);
            leaderContainer.toggleClass('d-none', isBranch);
            ownerContainer.addClass('d-none');

            $wire.tenant_type = null;
            $wire.sucursal = null;
            $wire.name = null;
            $wire.ci = null;
            $wire.resetErrors();
            resetFiels();
            removeAttr();

        });

        // Agregar manejador para el tipo de empresa
        $(document).on('change', `#addModal${TABLE_NAME} input[name="tenant_type_id"]`, function() {
            $wire.set('tenant_type', $(this).val());
        });

        function resetFiels() {
            $wire.name_user = null;
            $wire.lastname = null;
            $wire.phone = null;
            $wire.address = null;
            $wire.barrio = null;
            $wire.city = null;
        }

        function removeAttr() {
            const inputFields = ['ci', 'name_user', 'lastname', 'phone', 'address', 'barrio', 'city'];
            inputFields.forEach(fieldId => {
                $(`#${fieldId}`).prop('disabled', false)
                    .removeClass('bg-secondary-subtle');
            });
        }

        // Escuchar cambios en el input de CI 
        let ciTimeout;
        $(`#addModal${TABLE_NAME} .ci-input`).on({
            input: function() {
                clearTimeout(ciTimeout);
                ciTimeout = setTimeout(() => {
                    resetFiels();
                    removeAttr();
                    ownerContainer.addClass('d-none');
                }, 100);
            },
            keydown: function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    $(this).blur();
                }
            },
            blur: function() {
                const ci = $(this).val();
                $wire.ci = ci;
                $wire.dispatch('searchProfile', {
                    ci: ci
                });
            }
        });

        // Manejar respuesta cuando se encuentra el propietario
        $wire.on('ownerFound', () => {
            setTimeout(() => {
                const fields = ['ci', 'name_user', 'lastname', 'phone', 'address', 'barrio', 'city',
                    'email'
                ];
                fields.forEach(fieldId => {
                    $(`#${fieldId}`).prop('disabled', true)
                        .addClass('bg-secondary-subtle');
                });
                ownerContainer.removeClass('d-none');
                $(`#name`).focus();
            }, 100);
        });

        // Manejar respuesta cuando se encuentra el perfil
        $wire.on('profileFound', () => {
            setTimeout(() => {
                const fields = ['name_user', 'lastname', 'phone', 'address', 'barrio', 'city'];
                fields.forEach(fieldId => {
                    const input = $(`#${fieldId}`);
                    input.prop('disabled', true);
                    input.addClass('bg-secondary-subtle');
                });
                ownerContainer.removeClass('d-none');
                $(`#email`).focus();
            }, 100);
        });

        // Manejar respuesta cuando no se encuentra el perfil
        $wire.on('profileNotFound', () => {
            setTimeout(() => {
                ownerContainer.removeClass('d-none');
                $(`#name_user`).focus();
            }, 100);
        });

        // Manejar el tab en los inputs al presionar enter
        function setupEnterNavigation() {
            $('#addModal' + TABLE_NAME + ' [data-next]').on('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    const nextId = $(this).data('next');
                    if (nextId) {
                        $('#' + nextId).focus();
                    }
                }
            });
        }

        // Inicialización de la tabla
        function initDataTable(data) {
            if ($.fn.DataTable.isDataTable(`#${TABLE_NAME}`)) {
                table.destroy();
            }

            table = $(`#${TABLE_NAME}`).DataTable(getTableConfig(data));
            setupEventHandlers();
        }

        // Observador de cambios de tamaño
        function setupResizeObserver() {
            const container = document.getElementById('tableContainer');
            if (!container) return;

            const resizeObserver = new ResizeObserver(() => {
                clearTimeout(resizeTimeout);
                resizeTimeout = setTimeout(() => {
                    if (table && $.fn.DataTable.isDataTable(`#${TABLE_NAME}`)) {
                        const currentSearch = lastSearch;
                        table.search('').draw();
                        table.search(currentSearch).draw();
                    }
                }, 10);
            });

            resizeObserver.observe(container);
        }

        // Escuchar actualizaciones
        $wire.on('tableUpdated', ({
            datos
        }) => {
            lastSearch = table.search();
            lastPage = table.page();
            lastOrder = table.order();
            initDataTable(datos);
            table.search(lastSearch).draw();

            $(`#new_company`).prop('checked', true);

        });

        $wire.on('openCreateModal', () => {
            $wire.cancel();
            $(`#addModal${TABLE_NAME} .branch-select-container`).addClass('d-none');
            $(`#new_company`).prop('checked', true);
            $(`#addModal${TABLE_NAME}`).modal('show');

            setTimeout(() => {
                $(`#nameAdd${TABLE_NAME}`).focus();
            }, 1000);

            setTimeout(setupEnterNavigation, 300); // Delay para asegurar que el modal esté listo
        });

        // Iniciar la tabla
        initDataTable();
        setupResizeObserver();
    </script>
@endscript
