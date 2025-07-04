<!-- inicio Tabla Cientes -->
<div id="tableContainer" wire:ignore>
    <table id="{{ $componentName }}" width="100%" class="table table-hover text-nowrap">
        <thead class="bg-gray-900">
            <tr class="text-theme">
                <th></th> <!-- para el simbolo de responsive -->
                <th class="text-theme"></th> <!-- status -->
                <th class="text-theme"></th> <!-- proveedor -->
                <th class="text-theme"></th> <!-- fecha -->
                <th class="text-theme"></th> <!-- tipo comprobante -->
                <th class="text-theme"></th> <!-- numero -->
                <th class="text-theme"></th> <!-- total -->
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
                    render: () => '<div></div>',
                    className: 'noVis'

                },
                {
                    targets: 1,
                    title: "STATUS",
                    data: 'status',
                    sortable: false,
                    searchable: false,
                    render: (data) => {
                        if (data == 1) {
                            return '<span class="badge bg-info bg-opacity-15 text-info py-4px px-2 fs-9px d-inline-flex align-items-center"><i class="fa fa-circle opacity-5 fs-4px fa-fw me-2"></i>PAGADO</span>';
                        }
                        return '<span class="badge bg-danger bg-opacity-15 text-danger py-4px px-2 fs-9px d-inline-flex align-items-center"><i class="fa fa-circle opacity-5 fs-4px fa-fw me-2"></i>PENDIENTE</span>';
                    },
                },
                {
                    targets: 2,
                    title: "PROVEEDOR",
                    data: 'proveedor_name',
                    sortable: true,
                    searchable: true,
                    responsivePriority: 2,
                    className: 'noVis text-theme'
                },
                {
                    targets: 3,
                    title: "FECHA",
                    data: 'date_formatted',
                    sortable: true,
                    searchable: true,
                },
                {
                    targets: 4,
                    title: "TIPO",
                    data: 'tipoCompr',
                    sortable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        const tipos = {
                            1: 'F. Legal',
                            2: 'R. Común',
                            3: 'Otros'
                        };
                        return tipos[data] ||
                            data; // Si el valor existe en el mapeo, devolver la etiqueta, sino devolver el valor original
                    }
                },
                {
                    targets: 5,
                    title: "Nro. COMPROBANTE",
                    data: 'numero_factura',
                    sortable: false,
                    searchable: true,
                    responsivePriority: 3,
                    render: function(data, type, row) {
                        if (row.tipoCompr == 1) {
                            return row.numero_factura
                        } else {
                            return row.otrosRecibo || '--';
                        }
                    }
                },
                {
                    targets: 7,
                    title: "TOTAL",
                    data: 'total_formatted',
                    sortable: false,
                    searchable: true,
                    className: 'text-end',
                },
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
            console.log(data)
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
                },
                createdRow: function(row, data, dataIndex) {
                    $(row).on('click', function() {
                        $wire.dispatch('showCompraDetalle', {
                            data: {  
                                id: data.id,
                                date: data.date_formatted,
                                proveedor: data.proveedor_name,
                                nroFac: data.numero_factura,
                                nroRec: data.otrosRecibo,
                                timbrado: data.timbrado,
                                condCompra: data.condCompr,
                                tipoCompra: data.tipoCompr,
                                total: data.total, 
                                iva5x: data.iva_5,
                                iva10x: data.iva_10,
                                exc: data.exenta,
                                items: data.items,
                            }
                        });
                    });
                    $(row).css('cursor', 'pointer');
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
        $wire.on('tableUpdated', ({
            datos
        }) => {
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
