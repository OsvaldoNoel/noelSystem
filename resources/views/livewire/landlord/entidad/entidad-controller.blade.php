<div>
    @include('livewire.landlord.entidad.modals')

    <!-- inicio Tabla Bancos -->
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
                    <th></th>
                </tr>
            </thead>
        </table>
    </div>
    <!-- fin Tabla Bancos -->

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
                [5, 'asc']
            ],
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
                    title: "IMAGEN",
                    data: 'image',
                    className: 'text-start',
                    sortable: false,
                    searchable: false,
                    render: function(td, cellData, rowData) {
                        if (rowData['image'] == null) {
                            return "<div class='mb-1 bg-white rounded position-relative' style='height: 50px; width: 50px'><img src={{ asset('storage/sin_imagen.png') }}" +
                                " class='img-fluid position-absolute top-50 start-50 translate-middle rounded' style='max-width: 100%; max-height: 100%' /></div>"
                        } else {
                            return (
                                "<div class='mb-1 bg-white rounded position-relative' style='height: 50px; width: 50px'><img src={{ asset('storage/img/config/entidades/') }}/" +
                                rowData['image'] +
                                " class='img-fluid position-absolute top-50 start-50 translate-middle rounded' style='max-width: 100%; max-height: 100%' /></div>"
                            );
                        }
                    },
                },
                {
                    targets: 4,
                    title: "COLOR",
                    data: 'color',
                    className: 'text-start',
                    sortable: false,
                    searchable: false,
                    render: function(td, cellData, rowData) {
                        return (
                            "<div class='w-30px h-30px rounded " + rowData['color'] + " m-2'></div>" 
                        );

                    },
                }, 
                {
                    targets: 5,
                    title: "NOMBRE",
                    data: 'nombre',
                    className: 'text-theme',
                    sortable: false,
                    searchable: true,
                    responsivePriority: 3,
                },

                {
                    targets: 6,
                    title: "DIRECCIÓN",
                    data: 'addres',
                    sortable: false,
                    searchable: true,
                    responsivePriority: 2,
                },
                {
                    targets: 7,
                    title: "TELEFONO",
                    data: 'phone',
                    sortable: false,
                    searchable: false,
                },
                {
                    targets: 8,
                    title: "",
                    data: 'status',
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
            dom: "<'row align-content-between'<'col-7 mb-3'f><'col-5 mb-3 text-end'B>>t<'d-lg-flex align-items-center mt-3'> ",
            paging: true,
            lengthMenu: [{
                value: 10
            }],
            language: {
                info: 'Mostrando pág _PAGE_ de _PAGES_',
                infoEmpty: 'No se encontraron coincidencias',
                emptyTable: 'Aún no ha registrado ninguna entidad financiera',
                lengthMenu: 'Display _MENU_ records per page',
                infoFiltered: '(  _MAX_ registros en la tabla)',
                zeroRecords: "No se encontraron registros para la busqueda",
            },

            //agregamos un buton para agregar registros
            buttons: [{
                    extend: 'colvis',
                    columns: ':not(.noVis)',
                    className: 'bg-info text-black rounded d-none d-md-block',
                    text: 'Mostrar',
                },
                {
                    className: 'nav-link fs-3 btnPluss text-end mx-3',
                    text: '<i class="fa-solid fa-square-plus"></i>',
                    action: function() {

                        $wire.selected_id = null;
                        $wire.entidad = "Banco";
                        $wire.name = null;
                        $wire.addres = null;
                        $wire.phone = null;

                        $wire.dispatch('renderizar', {
                            color: 'bg-teal',
                            image: null
                        });

                        $('#addModal').modal('show');
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
            $wire.entidad = row['entidad'];
            $wire.name = row['name'];
            $wire.addres = row['addres'];
            $wire.phone = row['phone'];
            $wire.status = row['status'];

            $wire.dispatch('renderizar', {
                color: row['color'],
                image: row['image']
            });

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

        //recargamos la tabla despues de actualizarla
        $wire.$on(tableName, ($datos) => {
            data = JSON.parse($wire.datos);
            table.clear().rows.add(data).draw();
            console.log(data);
        })
    </script>
@endscript
