<!-- inicio Tabla Ususarios --> 
<div wire:ignore>
    <table id="{{ $componentName }}" width="100%" class="table table-hover text-nowrap">
        <thead class="bg-gray-900">
            <tr> 
                <th></th> 
                <th class="text-theme">ACT</th>
                <th class="text-theme text-center">C.I.</th>
                <th class="text-theme">NOMBRE</th> 
                <th class="text-theme">APELLIDO</th> 
                <th class="text-theme">TELEFONO</th>
                <th class="text-theme">EMAIL</th> 
                <th class="text-theme">DIRECCION</th>
                <th class="text-theme">BARRIO</th>
                <th class="text-theme">CIUDAD</th>
                <th></th> 
            </tr>
        </thead>  
    </table>
</div>  
<!-- fin Tabla Ususarios -->
 
 
<script>
    document.addEventListener('livewire:init', () => {  
        let tableName = 'Usuario';
        let busqueda  = '';
        let table = new DataTable("#" + tableName);
         
        Livewire.on(tableName, (datos) => {  
            //obtenemos los datos en una variabe en formato json  
            let data = JSON.parse(datos);   
            
            console.log('llega')

            //eliminamos la tabla existente e iniciamos otra con la data actualizada
            table.destroy();
            table = $("#" + tableName).DataTable({  
                    data: data,
                    responsive: !0,
                    order: [[3 , 'asc']],
                    columns: [  
                        { targets: 0, data: 'status', sortable: false, 
                            render: function (data) { 
                                return  '<div></div>';
                            },   
                        },
                        { targets: 1, data: 'status', sortable: false,  
                            render: function (td, cellData, rowData) { 
                                let checked = "";
                                if (rowData['status'] == 1) {
                                    checked = "checked";
                                };
                                return  '<div class="form-check form-switch"><input id="'+rowData['name']+rowData['id']+'" type="checkbox" '+ 
                                    checked +'  class="form-check-input btnStatus"></div>';
                            },
                        },
                        { targets: 2, data: 'ci', sortable: false}, 
                        { targets: 3, data: 'name', sortable: true, responsivePriority: 2}, 
                        { targets: 4, data: 'lastname', sortable: true},
                        { targets: 5, data: 'phone', sortable: false, searchable: false},
                        { targets: 6, data: 'email', sortable: false, searchable: false},  
                        { targets: 7, data: 'addres', sortable: false, searchable: false},
                        { targets: 8, data: 'barrio', sortable: false, searchable: false},
                        { targets: 9, data: 'city', sortable: true, searchable: false},
                        { targets: 9, data: 'status', sortable: false, searchable: false, responsivePriority: 1,
                            render: function (data) {
                                return '<div class="dropdown text-end"><a class="nav-link fs-5" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-ellipsis"></i></a>' + 
                                '<ul class="dropdown-menu bg-black">' + 
                                    '<li><a class="dropdown-item text-theme btnEditar"><i class="bi-pencil-square"></i> Edit </a></li>' +
                                    '<li><a class="dropdown-item text-theme btnEliminar"><i class="bi-trash3-fill"></i> Delete </a></li>' + 
                                '</ul>' +
                            '</div>';
                            },
                        }
                    ],   
                    dom:"<'row align-content-between'<'col-10 mb-3'f>>t<'d-lg-flex align-items-center mt-3' >",
                    scrollY: '41vh', 
                    lengthMenu:[{ value: -1 }],  
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
                
                $('div.dt-search input[type="search"]').attr('placeholder', 'Buscar...'); 
                $('div.dt-search label').hide();

                //cambiamos de status en la BD..
                $('#' + tableName +' tbody').on('click', '.btnStatus', function(){
                    let row = table.row($(this).parents('tr')).data(); 
                    Livewire.dispatch('status' + tableName, { id : row['id'] });  
                });

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
 