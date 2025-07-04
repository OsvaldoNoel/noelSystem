<div>   
    @include("livewire.tenant.stock.marcas.modals")

    <div wire:ignore>
        <table id="{{ $componentName }}" width="100%" class="table table-hover text-nowrap">
            <thead class="bg-gray-900">
                <tr> 
                    <th class="text-theme">MARCA</th> 
                    <th></th> 
                </tr>
            </thead>  
        </table>
    </div>   
</div>


<script>
    document.addEventListener('livewire:init', () => {  
        let tableName = 'Marca';
        let busqueda  = '';
        let table = new DataTable("#" + tableName);
         
        Livewire.on(tableName, (datos) => {  
            //obtenemos los datos en una variabe en formato json  
            let data = JSON.parse(datos);  

            //eliminamos la tabla existente e iniciamos otra con la data actualizada
            table.destroy();
            table = $("#" + tableName).DataTable({  
                    data: data,  
                    columns: [ 
                        { data: 'name' },   
                    ],

                    //agregamos botones en cada fila
                    columnDefs: [
                        {
                            targets: 1,  
                            sortable: false,
                            render: function () {
                                return '<div class="dropdown text-end"><a class="nav-link fs-5" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-ellipsis"></i></a>' + 
                                '<ul class="dropdown-menu bg-black">' + 
                                    '<li><a class="dropdown-item text-theme btnEditar"><i class="bi-pencil-square"></i> Edit </a></li>' +
                                    '<li><a class="dropdown-item text-theme btnEliminar"><i class="bi-trash3-fill"></i> Delete </a></li>' + 
                                '</ul>' +
                            '</div>';
                            },
                        }
                    ],   
                    dom:"<'row align-content-between'<'col-10 mb-0'f><'col-2 mb-0'B>>t<'d-lg-flex align-items-center mt-3' >",
                    scrollY: '41vh', 
                    lengthMenu:[{ value: -1 }],

                    //agregamos un buton para agregar registros
                    buttons:[
                        {
                            className: 'nav-link fs-3 btnPluss',
                            text: '<i class="fa-solid fa-square-plus"></i>',
                                    action: function () {
                                        Livewire.dispatch('add' + tableName);
                                        busqueda = table.search(); 
                                    }
                                }
                    ],  
                });

                //hacemos el scholl del body en la tabla
                $('.dt-scroll-body').niceScroll({
                    cursorwidth:3,
                    cursoropacitymin:0.6,
                    cursorcolor: 'rgb(0 0 0 / 50%)',
                    cursorborder:'none',
                    cursorborderradius:4,
                    autohidemode:'leave'
                });  

                //colocamos el ultimo valor buscado antes de editar
                table.search(busqueda).draw(); 
                
                $('div.dt-search input[type="search"]').attr('placeholder', 'Buscar...').attr('autocomplete', 'off'); 
                $('div.dt-search label').hide();

                //enviamos eventos de edit y delete al controlador
                $('#' + tableName +' tbody').on('click', '.btnEditar', function(){
                    let row = table.row($(this).parents('tr')).data(); 
                    busqueda = table.search();
                    Livewire.dispatch('edit' + tableName, { id : row['id'] }); 
                });

                $('#' + tableName +' tbody').on('click', '.btnEliminar', function(){
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
        });
    });
</script>
