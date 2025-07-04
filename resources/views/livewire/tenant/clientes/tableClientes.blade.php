<!-- inicio Tabla Cientes -->
<div id="tableContainer" wire:ignore>
    <table id="{{ $componentName }}" width="100%" class="table table-hover text-nowrap">
        <thead class="bg-gray-900">
            <tr class="text-theme">
                <th></th>
                <th class="text-theme"></th>
                <th class="text-theme"></th>
                <th class="text-theme"></th>
                <th class="text-theme"></th>
                <th class="text-theme"></th>
                <th class="text-theme"></th>
                <th class="text-theme"></th>
                <th class="text-theme"></th>
                <th class="text-theme"></th>
            </tr>
        </thead>
    </table>
</div>
<!-- fin Tabla Clientes -->


@script
    <script>
        const TABLE_NAME = $wire.componentName;
        let table;
        let lastSearch = '';
        let lastPage = 0;
        let lastOrder = [
            [3, 'asc']
        ];
        let resizeTimeout;

        // Configuración de columnas
        function getColumnsConfig() {
            return [{
                    targets: 0,
                    title: "",
                    data: 'status',
                    sortable: false,
                    className: 'noVis',
                    render: () => '<div></div>',
                },
                {
                    targets: 1,
                    title: "switch",
                    data: 'status',
                    sortable: false,
                    searchable: false,
                    visible: false,
                    render: (td, cellData, rowData) => {
                        const checked = rowData.status == 1 ? "checked" : "";
                        return `<div class="form-check form-switch">
                            <input id="${rowData.name}${rowData.id}" type="checkbox" ${checked} class="form-check-input btnStatus">
                        </div>`;
                    },
                },
                {
                    targets: 2,
                    title: "STATUS",
                    data: 'status',
                    sortable: false,
                    searchable: false,
                    render: (data) => {
                        if (data == 1) {
                            return '<span class="badge bg-info bg-opacity-15 text-info py-4px px-2 fs-9px d-inline-flex align-items-center"><i class="fa fa-circle opacity-5 fs-4px fa-fw me-2"></i> ACTIVO</span>';
                        }
                        return '<span class="badge bg-danger bg-opacity-15 text-danger py-4px px-2 fs-9px d-inline-flex align-items-center"><i class="fa fa-circle opacity-5 fs-4px fa-fw me-2"></i> INACTIVO</span>';
                    },
                },
                {
                    targets: 3,
                    title: "NOMBRE",
                    data: 'name',
                    sortable: true,
                    responsivePriority: 2,
                    className: 'noVis text-theme'
                },
                {
                    targets: 4,
                    title: "RUC",
                    data: 'ruc',
                    render: (data, type, row) => `${row.ruc}-${row.dv}`,
                    sortable: false,
                    searchable: true,
                },
                {
                    targets: 5,
                    title: "TELEFONO",
                    data: 'phone',
                    sortable: false,
                    searchable: true,
                    responsivePriority: 3,
                },
                {
                    targets: 6,
                    title: "DIRECCION",
                    data: 'adress',
                    sortable: false,
                    searchable: false,
                },
                {
                    targets: 7,
                    title: "BARRIO",
                    data: 'barrio',
                    sortable: false,
                    searchable: false,
                },
                {
                    targets: 8,
                    title: "CIUDAD",
                    data: 'city',
                    sortable: false,
                    searchable: false,
                },
                {
                    targets: 9,
                    title: "E-MAIL",
                    data: 'email',
                    sortable: false,
                    searchable: false,
                },
                {
                    targets: 10,
                    title: "",
                    data: 'status',
                    sortable: false,
                    searchable: false,
                    className: 'noVis',
                    responsivePriority: 1,
                    render: () => `
                    <div class="dropdown text-end">
                        <a class="nav-link fs-5" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-ellipsis"></i>
                        </a>
                        <ul class="dropdown-menu bg-black">
                            <li><a class="dropdown-item text-theme btnEditar"><i class="bi-pencil-square"></i> Edit</a></li>
                            <li><a class="dropdown-item text-theme btnEliminar"><i class="bi-trash3-fill"></i> Delete</a></li>
                        </ul>
                    </div>`,
                }
            ];
        }

        // Configuración responsive
        function getResponsiveConfig() {
            return {
                details: {
                    renderer: (api, rowIdx, columns) => {
                        const data = columns.map(col =>
                            col.hidden ? `
                            <tr data-dt-row="${col.rowIndex}" data-dt-column="${col.columnIndex}">
                                <td>${col.title} : </td>
                                <td>${col.data}</td>
                            </tr>` : ''
                        ).join('');

                        return data ? $('<table/>').html(data) : false;
                    }
                }
            };
        }

        // Configuración de botones
        function getButtonsConfig() {
            return [{
                    extend: 'colvis',
                    columns: ':not(.noVis)',
                    className: 'bg-info text-black rounded d-none d-lg-block',
                    text: 'Mostrar',
                },
                {
                    className: 'nav-link fs-3 btnPluss text-end mx-3',
                    text: '<i class="fa-solid fa-square-plus"></i>',
                    action: () => {
                        $wire.selected_id = null;
                        $wire.name = null;
                        $wire.ruc = null;
                        $wire.dv = null;
                        $wire.barrio = null;
                        $wire.city = null;
                        $wire.adress = null;
                        $wire.phone = null;
                        $wire.email = null;
                        $('#addModal').modal('show');
                    }
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
                responsive: getResponsiveConfig(),
                order: lastOrder,
                columns: getColumnsConfig(),
                dom: "<'row align-content-between'<'col-7 mb-3'f><'col-5 mb-3 text-end'B>>t<'d-lg-flex align-items-center mt-3'<'me-auto mb-lg-0 mb-3'i><'mb-0'p> ",
                scrollY: '64vh',
                paging: true,
                lengthMenu: [
                    [15],
                    [15]
                ],
                pageLength: 15,
                language: getLanguageConfig(),
                buttons: getButtonsConfig(),
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
                const row = table.row($(this).parents('tr')).data();
                row.status = row.status == 0 ? 1 : 0;
                table.row($(this).parents('tr')).data(row).draw();
                Livewire.dispatch(`status${TABLE_NAME}`, {
                    id: row.id
                });
            });

            // Editar
            $(`#${TABLE_NAME} tbody`).on('click', '.btnEditar', function() {
                const row = table.row($(this).parents('tr')).data();
                Object.entries({
                    selected_id: row.id,
                    name: row.name,
                    ruc: row.ruc,
                    dv: row.dv,
                    barrio: row.barrio,
                    city: row.city,
                    adress: row.adress,
                    phone: row.phone,
                    email: row.email
                }).forEach(([key, value]) => $wire[key] = value);
                $('#editModal').modal('show');
            });

            // Eliminar
            $(`#${TABLE_NAME} tbody`).on('click', '.btnEliminar', function() {
                const row = table.row($(this).parents('tr')).data();
                window.Livewire.dispatch('swal:confirm', {
                    background: 'info',
                    html: `Eliminar <b><i>"${row.name}"</i></b> del registro?`,
                    next: {
                        event: `delete${TABLE_NAME}`,
                        params: {
                            id: row.id
                        }
                    }
                });
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

            // Limpiar observer cuando el componente se destruya
            document.addEventListener('livewire:init', () => {
                Livewire.on('destroy', () => {
                    resizeObserver.unobserve(container);
                });
            });
        } 

        // Escuchar actualizaciones
        $wire.on('tableUpdated', ({ datos }) => {
            lastSearch = table.search();
            lastPage = table.page();
            lastOrder = table.order();
            initDataTable(datos);
            table.search(lastSearch).draw();
        });

        // Iniciar la tabla
        initDataTable();
        setupResizeObserver();
    </script>
@endscript
