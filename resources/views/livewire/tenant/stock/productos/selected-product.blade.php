<div>
    <div class="flex-fill position-relative z-1">
        <div class="input-group">
            <div class="input-group-text position-absolute top-0 bottom-0 bg-none border-0">
                <i class="fa fa-search opacity-5"></i>
            </div>
            <input id="search" type="text" onkeyup="siVacioOcultar()" onkeydown="keyCode(event)"
                class="form-control ps-30px" placeholder="Ingresa nombre o código del producto" autocomplete="off">
        </div>

        <div id="tableProduct" class="position-absolute w-100 dt-scroll rounded p-3"
            style="z-index: 1000; max-height: 550px; overflow-y: auto; display: none; background: rgb(0, 32, 36)">

            <!-- inicio Tabla de Productos -->
            <div wire:ignore>
                <table id="{{ $componentName }}" width="100%" class="table table-hover text-nowrap">
                    <thead class="bg-gray-900">
                        <tr class="text-theme">
                            <th class="text-theme"></th>
                            <th class="text-theme"></th>
                            <th class="text-theme"></th>
                            <th class="text-theme"></th>
                            <th class="text-theme"></th>
                        </tr>
                    </thead>
                </table>
            </div>
            <!-- fin Tabla de Productos -->

        </div>
    </div>
</div>

@script
    <script>
        let tableName = $wire.componentName;
        let data = JSON.parse($wire.datos);
        let table = $("#" + tableName).DataTable({
            data: data,
            language: {
                zeroRecords: "No se encontraron productos para la busqueda",
            },
            order: [
                [2, 'asc']
            ],
            columnDefs: [{
                render: function(data, type, row) {
                    return puntoSeparateNumber(data);
                },
                targets: [3, 4]
            }],
            columns: [

                {
                    targets: 0,
                    title: "CODIGO",
                    className: 'text-center',
                    data: 'code',
                    sortable: false
                },
                {
                    targets: 1,
                    data: 'image',
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
                    targets: 2,
                    title: "ARTICULO",
                    sortable: false,
                    responsivePriority: 2,
                    className: 'text-theme',
                    render: function(td, cellData, rowData) {
                        return rowData['name'] + ' ' + rowData['marca_name'] + ' -- ' + rowData[
                            'presentation_name'];
                    }
                },

                {
                    targets: 3,
                    title: "PRECIO",
                    data: 'priceList2',
                    sortable: false,
                    responsivePriority: 3,
                    searchable: false,
                    className: 'text-theme'
                },

                {
                    targets: 4,
                    title: "STOCK",
                    data: 'stock',
                    sortable: false,
                    responsivePriority: 4,
                    searchable: false
                },

            ],
            dom: "<'row align-content-between'>t<'d-lg-flex align-items-center mt-3' >",
            lengthMenu: [{
                value: 15
            }],

            //pintamos un color distinto a stock cero 
            rowCallback: function(row, data) {
                if (data.stock == 7) {
                    $(row).addClass('bg-danger-subtle');
                }
            }
        });

        $('#Producto tbody').on('click', 'tr', function() {
            Livewire.dispatch('selectedProduct', {
                product: table.row(this).data().id,
                marca: table.row(this).data().marca_name,
                presentation: table.row(this).data().presentation_name,
            });
            table.search('').draw();
        });

        $('#search').on('keyup', function() {
            table.search(this.value).draw();
        });

        $('.dt-scroll').niceScroll({
            cursorwidth: 3,
            cursoropacitymin: 0.6,
            cursorcolor: 'rgb(0 0 0 / 50%)',
            cursorborder: 'none',
            cursorborderradius: 4,
            autohidemode: 'leave'
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

<script>
    let search = document.getElementById("search");
    let div = document.getElementById('tableProduct');
    search.focus(); 

    //vaciamos el input despues del scann
    document.addEventListener('livewire:init', () => {
        Livewire.on('scanCode', () => {
            search.value = '';
        })
    })

    //mostramos si no esta vacio el input despues de 5 milisegundos
    function debounce(func, timeout = 5) {
        let timer;
        return (...args) => {
            clearTimeout(timer);
            timer = setTimeout(() => {
                func.apply(this, args);
            }, timeout);
        };
    }

    function cargarInput() {
        search.value == "" ? div.style.display = 'none' : div.style.display = 'block';
    }
    const siVacioOcultar = debounce(() => cargarInput());

    //----------



    //detectamos un click fuera del elemento search
    const onClickOutside = (element, callback) => {
        document.addEventListener('click', e => {
            if (!element.contains(e.target)) callback();
        });
    };
    onClickOutside(search, () => {
        search.value = "";
        div.style.display = 'none';
    });

    //detectamos la tecla escape
    function keyCode(event) {
        var x = event.keyCode;
        if (x == 27) {
            search.value = "";
            div.style.display = 'none'
        }
    }

</script>
