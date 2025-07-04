<!-- inicio Tabla Ususarios -->
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
<!-- fin Tabla Ususarios -->

@script
    <script>
        //-------------abrir selector de archivos---------
        function abrir() {
            var file = document.getElementById("file").click();
        }

        //-------------cargar tabla-------------------
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
                [7, 'asc']
            ],
            columnDefs: [{
                render: function(data, type, row) {
                    return puntoSeparateNumber(data);
                },
                targets: [2, 3, 4, 5, 11, 12, 13]
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
                    title: "ACT",
                    data: 'status',
                    sortable: false,
                    className: 'noVis',
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
                    title: "STOCK",
                    data: 'stock',
                    sortable: false,
                    responsivePriority: 4,
                    searchable: false
                },
                {
                    targets: 3,
                    title: "LISTA 1",
                    data: 'priceList1',
                    sortable: false,
                    searchable: false
                },
                {
                    targets: 4,
                    title: "LISTA 2",
                    data: 'priceList2',
                    sortable: false,
                    responsivePriority: 3,
                    searchable: false,
                    className: 'text-theme'
                },
                {
                    targets: 5,
                    title: "LISTA 3",
                    data: 'priceList3',
                    sortable: false,
                    searchable: false
                },
                {
                    targets: 6,
                    title: "IMAGEN",
                    data: 'image',
                    className: 'text-start',
                    sortable: false,
                    searchable: false,
                    render: function(td, cellData, rowData) {
                        if (rowData['image'] == null) {
                            return "<div class='mb-1 bg-black rounded position-relative' style='height: 80px; width: 80px'><img src={{ asset('storage/sin_imagen.png') }}" +
                                " class='img-fluid position-absolute top-50 start-50 translate-middle rounded' style='max-width: 100%; max-height: 100%' /></div>"
                        } else {
                            return (
                                "<div class='mb-1 bg-black rounded position-relative' style='height: 80px; width: 80px'><img src={{ asset('storage/img/') }}/" +
                                rowData['tenant_id'] + "/productos/" + rowData['image'] +
                                " class='img-fluid position-absolute top-50 start-50 translate-middle rounded' style='max-width: 100%; max-height: 100%' /></div>"
                            );
                        }
                    },
                },
                {
                    targets: 7,
                    title: "ARTICULO",
                    data: 'name',
                    sortable: true,
                    responsivePriority: 2,
                    className: 'noVis text-theme'
                },
                {
                    targets: 8,
                    title: "PRESENTACION",
                    data: 'presentation_name',
                    sortable: true,
                    searchable: false,
                    responsivePriority: 5,
                },
                {
                    targets: 9,
                    title: "MARCA",
                    data: 'marca_name',
                    sortable: true,
                    searchable: false,
                    responsivePriority: 6,
                },
                {
                    targets: 9,
                    title: "CATEGORIA",
                    data: 'categoria_name',
                    sortable: true,
                    searchable: false,
                    responsivePriority: 7,
                },
                {
                    targets: 10,
                    title: "SUBCATEGORIA",
                    data: 'subcategoria_name',
                    sortable: true,
                    searchable: false,
                    responsivePriority: 8,
                },
                {
                    targets: 11,
                    title: "STOCK MIN",
                    data: 'stockMin',
                    sortable: true,
                    searchable: false
                },
                {
                    targets: 12,
                    title: "STOCK MAX",
                    data: 'stockMax',
                    sortable: false,
                    searchable: false
                },
                {
                    targets: 13,
                    title: "COSTO PROMEDIO",
                    data: 'costoPromedio',
                    sortable: false,
                    searchable: false
                },
                {
                    targets: 14,
                    title: "CODIGO",
                    data: 'code',
                    sortable: false
                },
                {
                    targets: 15,
                    title: "VENCIMIENTO",
                    data: 'vto',
                    sortable: false,
                    render: function(td, cellData, rowData) {
                        let checked = "";
                        if (rowData['vto'] == 1) {
                            checked = "checked";
                        };
                        return '<div class="form-check form-switch"><input id="' + rowData['name'] +
                            rowData['id'] + rowData['name'] + '" type="checkbox" ' +
                            checked + '  class="form-check-input btnVto"></div>';
                    },
                },
                {
                    targets: 16,
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
            dom: "<'row align-content-between'<'col-7 mb-3'f><'col-5 mb-3 text-end'B>>t<'d-lg-flex align-items-center mt-3' >",
            scrollY: '64vh',
            lengthMenu: [{
                value: -1
            }],

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
                        Livewire.dispatch('add' + tableName);
                        busqueda = table.search();
                    }
                },
            ],
            rowCallback: function(row, data) {
                if (data.stock == 7) { 
                    $(row).addClass('bg-danger-subtle');
                }
            }
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

        //cambiamos de status en la BD..
        $('#' + tableName + ' tbody').on('click', '.btnStatus', function() {
            let row = table.row($(this).parents('tr')).data();
            Livewire.dispatch('status' + tableName, {
                id: row['id']
            });
        });

        //cambiamos el Vto en la BD..
        $('#' + tableName + ' tbody').on('click', '.btnVto', function() {
            let row = table.row($(this).parents('tr')).data();
            Livewire.dispatch('vto' + tableName, {
                id: row['id']
            });
        });

        //enviamos eventos de edit y delete al controlador
        $('#' + tableName + ' tbody').on('click', '.btnEditar', function() {
            let row = table.row($(this).parents('tr')).data();
            busqueda = table.search();
            Livewire.dispatch('edit' + tableName, {
                id: row['id']
            });
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

        $wire.$on(tableName, ($datos) => {
            data = JSON.parse($wire.datos);
            table.clear().rows.add(data).draw();
        })
    </script>
@endscript
