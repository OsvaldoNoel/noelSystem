<div>
    @include('livewire.tenant.finanzas.aportantes.modals')

    <div id="proveedors">
        <div class="card">
            <div class="card-header with-btn">
                APORTES DE CAPITAL - PRESTAMOS
                <div class="card-header-btn">
                    <a href="#" data-toggle="card-collapse" class="btn"><iconify-icon
                            icon="material-symbols-light:stat-minus-1"></iconify-icon></a>
                    <a href="#" data-toggle="card-expand" class="btn"><iconify-icon
                            icon="material-symbols-light:fullscreen"></iconify-icon></a>
                    <a href="#" data-toggle="card-remove" class="btn"><iconify-icon
                            icon="material-symbols-light:close-rounded"></iconify-icon></a>
                </div>
            </div>
            <div class="card-body">
                <div wire:ignore>
                    <table id="{{ $componentName }}" width="100%" class="table table-hover text-nowrap">
                        <thead class="bg-gray-900">
                            <tr class="text-theme">
                                <th></th>
                                <th class="text-theme"></th>
                                <th class="text-theme"></th>
                                <th class="text-theme"></th>
                                <th class="text-theme"></th>
                                <th></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@script
    <script>
        let tableName = $wire.componentName;
        let busqueda = '';

        //obtenemos los datos en una variabe en formato json
        let data = JSON.parse($wire.datos);

        //creamos la tabla
        let table = $("#" + tableName).DataTable({
            data: data,
            responsive: {
                details: {
                    renderer: function(api, rowIdx, columns) {
                        let data = columns
                            .map((col, i) => {
                                return col.hidden ?
                                    '<tr data-dt-row="' + col.rowIndex + '" data-dt-column="' + col
                                    .columnIndex + '">' +
                                    '<td>' + col.title + '  : ' + '</td> ' + '<td>' + col.data + '</td>' +
                                    '</tr>' :
                                    '';
                            }).join('');

                        let table = document.createElement('table');
                        table.innerHTML = data;
                        return data ? table : false;
                    }
                }
            },
            order: [
                [2, 'asc']
            ],
            columnDefs: [{
                render: function(data, type, row) {
                    return puntoSeparateNumber(data);
                },
                targets: [4,]
            }],
            columns: [{
                    targets: 0,
                    title: "",
                    data: 'status',
                    sortable: false,
                    className: 'noVis',
                    render: function(data) {
                        return '<div></div>';
                    },
                },

                {
                    targets: 1,
                    title: "switch",
                    data: 'status',
                    sortable: false,
                    searchable: false,
                    visible: false,
                    render: function(td, cellData, rowData) {
                        let checked = "";
                        if (rowData['status'] == 1) {
                            checked = "checked";
                        };
                        return '<div class="form-check form-switch"><input id="' + rowData['name'] +
                            rowData['id'] + '" type="checkbox" ' +
                            checked + '  class="form-check-input btnStatus"></div>';
                    },
                },
                {
                    targets: 2,
                    title: "STATUS",
                    data: 'status',
                    sortable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        if (data == 1) {
                            return '<span class="badge bg-info bg-opacity-15 text-info py-4px px-2 fs-9px d-inline-flex align-items-center"><i class="fa fa-circle opacity-5 fs-4px fa-fw me-2"></i> ACTIVO</span>';
                        };
                        if (data == 0) {
                            return '<span class="badge bg-danger bg-opacity-15 text-danger py-4px px-2 fs-9px d-inline-flex align-items-center"><i class="fa fa-circle opacity-5 fs-4px fa-fw me-2"></i> INACTIVO</span>';
                        };
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
                    title: "APORTE",
                    data: 'aporte',
                    sortable: false,
                    responsivePriority: 3,
                    className: 'noVis text-end'
                }, 
                // {
                //     targets: 6,
                //     title: "SALDO",
                //     data: 'id',
                //     render: function(data, type, row, meta) {
                //         saldo = row['aporte'] - row['devolucion'];

                //         while (/(\d+)(\d{3})/.test(saldo.toString())) {
                //             saldo = saldo.toString().replace(/(\d+)(\d{3})/, '$1' + '.' + '$2');
                //         }

                //         return saldo; 
                //     },
                //     sortable: false,
                //     responsivePriority: 5,
                //     className: 'noVis text-end'
                // },
                {
                    targets: 5,
                    title: "",
                    data: 'status',
                    sortable: false,
                    searchable: false,
                    className: 'noVis',
                    responsivePriority: 1,
                    render: function(data) {
                        return '<div class="dropdown text-end"><a class="nav-link fs-5" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-ellipsis"></i></a>' +
                            '<ul class="dropdown-menu bg-black">' +
                            '<li><a class="dropdown-item text-theme btnEditar pointer"><i class="bi-pencil-square"></i> Editar nombre </a></li>' +
                            '<li><a class="dropdown-item text-theme btnDetalle pointer"><i class="bi-trash3-fill"></i> Detalle </a></li>' +
                            '</ul>' +
                            '</div>';
                    },
                }
            ],
            dom: "<'row align-content-between'<'col-7 mb-3'f><'col-5 mb-3 text-end'B>>t<'d-lg-flex align-items-center mt-3'<'me-auto mb-lg-0 mb-3'i><'mb-0'p> ",

            //dom:"<'row mb-3'<'col-sm-4'l><'col-sm-8 text-end'<'d-lg-flex justify-content-end'<'mb-3 mb-lg-0'f>B>>>t<'d-lg-flex align-items-center mt-3'<'me-auto mb-lg-0 mb-3'i><'mb-0'p>>",
            paging: true,
            lengthMenu: [{
                value: 15
            }],
            language: {
                info: 'Mostrando pág _PAGE_ de _PAGES_',
                infoEmpty: 'No se encontraron coincidencias',
                lengthMenu: 'Display _MENU_ records per page',
                infoFiltered: '(  _MAX_ registros en la tabla)',
                zeroRecords: "No se encontraron registros para la busqueda",
                emptyTable: 'La tabla aun no cuenta con registros',
            },

            //agregamos un buton para agregar registros
            buttons: [{
                    extend: 'colvis',
                    columns: ':not(.noVis)',
                    className: 'bg-info text-black rounded d-none d-lg-block',
                    text: 'Mostrar',
                },
                {
                    className: 'nav-link fs-3 btnPluss text-end mx-3',
                    text: '<i class="fa-solid fa-square-plus"></i>',
                    action: function() {

                        $wire.selected_id = null;
                        $wire.name = null;

                        $('#addModal').modal('show');
                        $wire.cancel();
                    }
                },
            ],
        });

        //hacemos el scholl del body en la tabla
        $('.dt-scroll-body').niceScroll({
            cursorwidth: 3,
            cursoropacitymin: 0.6,
            cursorcolor: 'rgb(0 0 0 / 50%)',
            cursorborder: 'none',
            cursorborderradius: 4,
            autohidemode: 'leave'
        });

        //colocamos el ultimo valor buscado antes de editar
        table.search(busqueda).draw();

        $('div.dt-search input[type="search"]').attr('placeholder', 'Buscar...').attr('autocomplete', 'off');
        $('div.dt-search label').hide();

        //cambiamos de status
        $('#' + tableName + ' tbody').on('click', '.btnStatus', function() {
            let row = table.row($(this).parents('tr')).data();
            row['status'] == 0 ? row['status'] = 1 : row['status'] = 0;

            //repintamos la fila sin reconsultar la BD..
            let newRow = table.row($(this).parents('tr')).data();
            table.row($(this).parents('tr')).data(newRow).draw();

            //actualizamos en la BD..
            Livewire.dispatch('status' + tableName, {
                id: row['id']
            });
        });

        //enviamos eventos de edit y delete al controlador
        $('#' + tableName + ' tbody').on('click', '.btnEditar', function() {
            let row = table.row($(this).parents('tr')).data();

            $wire.selected_id = row['id'];
            $wire.name = row['name'];

            $('#editModal').modal('show');
        });

        $('#' + tableName + ' tbody').on('click', '.btnDetalle', function() {
            let row = table.row($(this).parents('tr')).data();

            // $wire.selected_id = row['id'];
            // $wire.name = row['name'];

            Livewire.dispatch('aportanteDetalle', {
                id: row['id']
            });

            $('#modalDetalle').modal('show'); 
        }); 

        //funcion para agregar separadores decimales
        function puntoSeparateNumber(val) {
            while (/(\d+)(\d{3})/.test(val.toString())) {
                val = val.toString().replace(/(\d+)(\d{3})/, '$1' + '.' + '$2');
            }
            return val;
        };

        //recargamos la tabla despues de actualizarla  
        $wire.$on(tableName, ($datos) => {
            data = JSON.parse($wire.datos);
            table.clear().rows.add(data).draw();
        })
    </script>
@endscript
