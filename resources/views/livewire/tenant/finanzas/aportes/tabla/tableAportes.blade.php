<!-- inicio Tabla Aportes -->
<div wire:ignore>
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
                <th></th>
            </tr>
        </thead>
    </table>
</div>
<!-- fin Tabla Aportes -->


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
                [1, 'desc']
            ],
            columnDefs: [{
                    render: function(data, type, row) {
                        return puntoSeparateNumber(data);
                    },
                    targets: [8]
                }, 
                {
                    targets: 1,
                    render: $.fn.dataTable.render.moment('DD-MM-YYYY h:mm', 'DD-MMM-YYYY', 'es')
                }
            ],

            columns: [{
                    targets: 0,
                    title: "",
                    data: 'id',
                    sortable: false,
                    className: 'noVis',
                    render: function(data) {
                        return '<div></div>';
                    },
                },
                {
                    targets: 1,
                    title: "FECHA",
                    data: 'date',
                    sortable: true,
                    className: 'text-center',
                },
                {
                    targets: 2,
                    title: "APORTANTE",
                    data: 'aportante_name',
                    sortable: true,
                    responsivePriority: 1,
                    className: 'noVis text-theme'
                }, 
                {
                    targets: 3,
                    title: "APORTE",
                    data: 'operacion',
                    sortable: false,
                    className: 'noVis text-end',
                    render: function(td, cellData, rowData) {
                        if (rowData['operacion'] == 0) {
                            return puntoSeparateNumber(rowData['monto']);
                        } else {
                            return '-'
                        };
                    },
                },
                {
                    targets: 4,
                    title: "Enviado a:",
                    data: 'entidad_nombre',
                    sortable: false,
                    className: 'noVis',
                    render: function(td, cellData, rowData) {
                        if (rowData['operacion'] == 0) {
                            if (rowData['entidad_nombre'] == null) {
                                return 'Caja Tesoreria';
                            } else {
                                return rowData['entidad_nombre']
                            };
                        } else {
                            return null
                        };
                    },
                },
                {
                    targets: 5,
                    title: "RETIROS",
                    data: 'operacion',
                    sortable: false,
                    className: 'noVis text-end',
                    render: function(td, cellData, rowData) {
                        if (rowData['operacion'] == 1) {
                            return puntoSeparateNumber(rowData['monto']);
                        } else {
                            return '-'
                        };
                    },
                },
                {
                    targets: 6,
                    title: "Retirado de:",
                    data: 'entidad_nombre',
                    sortable: false,
                    className: 'noVis',
                    render: function(td, cellData, rowData) {
                        if (rowData['operacion'] == 1) {
                            if (rowData['entidad_nombre'] == null) {
                                return 'Caja Tesoreria';
                            } else {
                                return rowData['entidad_nombre']
                            };
                        } else {
                            return null
                        };
                    },
                },
                {
                    targets: 7,
                    title: "Detalle",
                    data: 'detail',
                    sortable: false,
                    className: 'noVis'
                }, 
                {
                    targets: 8,
                    title: "SALDO",
                    data: 'saldo',
                    sortable: false,
                    className: 'noVis text-end'
                },
                {
                    targets: 9,
                    title: "",
                    data: 'id',
                    sortable: false,
                    searchable: false,
                    className: 'noVis',
                    responsivePriority: 1,
                    render: function(data) {
                        return '<div class="dropdown text-end"><a class="nav-link fs-5" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-ellipsis"></i></a>' +
                            '<ul class="dropdown-menu bg-black">' +
                            '<li><a class="dropdown-item text-theme btnEditar"><i class="bi-pencil-square"></i> Edit </a></li>' +
                            '<li><a class="dropdown-item text-theme btnEliminar"><i class="bi-trash3-fill"></i> Delete </a></li>' +
                            '</ul>' +
                            '</div>';
                    },
                }
            ],
            dom: "<'row align-content-between'<'col-7 mb-3'f><'col-5 mb-3 text-end'B>>t<'d-lg-flex align-items-center mt-3'<'me-auto mb-lg-0 mb-3'i><'mb-0'p> ",
            scrollY: '64vh',
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
            buttons: [],
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

        // table.column( 4 ).data().sum();

        $('div.dt-search input[type="search"]').attr('placeholder', 'Buscar...').attr('autocomplete', 'off');
        $('div.dt-search label').hide();

        //enviamos eventos de edit y delete al controlador
        $('#' + tableName + ' tbody').on('click', '.btnEditar', function() {
            let row = table.row($(this).parents('tr')).data();

            $wire.selected_id = row['id'];
            $wire.name = row['name'];
            $wire.ruc = row['ruc'];
            $wire.dv = row['dv'];
            $wire.barrio = row['barrio'];
            $wire.city = row['city'];
            $wire.adress = row['adress'];
            $wire.phone = row['phone'];
            $wire.email = row['email'];

            $('#editModal').modal('show');
        });

        $('#' + tableName + ' tbody').on('click', '.btnEliminar', function() {
            let row = table.row($(this).parents('tr')).data();
            busqueda = table.search();
            window.Livewire.dispatch('swal:confirm', {
                background: 'info',
                html: 'Eliminar <b><i>"' + row['name'] + '"</i></b> del registro?',
                next: {
                    event: 'delete' + tableName,
                    params: {
                        id: row['id'],
                    }
                }
            });
        });

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
            console.log(data)
        })
    </script>
@endscript
