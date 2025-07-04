<div wire:ignore>
    <div class="form-group">
        <label for="select-cliente" class="mb-0 text-muted">Cliente</label>
        <div class="input-group">
            <span wire:click="$dispatch('addCliente')" class="input-group-text text-success pointer"><Span class="fa fa-plus"></Span></span>
            <select class="form-select text-theme" id="select-cliente" placeholder="Buscar cliente..."
                autocomplete="off"> 
            </select>   
        </div>
    </div>
</div>

@script
    <script>
        let newName = new TomSelect("#select-cliente", {});

        $wire.$on('valorData', ($id) => {
            let options = JSON.parse($wire.options) 
            newName.destroy();
            newName = new TomSelect("#select-cliente", {
                plugins: ['dropdown_input'],
                create: false,
                items: $id,
                options: options,
                valueField: 'id',
                //labelField: 'name',
                searchField: ['name','ruc'],
                hideSelected: true,
                //openOnFocus: false,
                sortField: {
                    field: "name",
                    direction: "asc",
                },
                itemClass: 'text-theme',
                render: {
                    option: function(data, escape) {
                        return `<div class="row"><div class="col-8">${data.name}</div><div class="col-4 text-end">${data.ruc}-${data.dv}</div></div>`;
                    },
                    item: function(item, escape) {
                        return `<div>${item.name} ---- ${item.ruc}-${item.dv}</div>`;
                    }
                },
                onChange: function() {
                    let id = newName.items[0];
                    if (id != null) {
                        Livewire.dispatch('selectedCliente', {
                            id
                        });
                    } 
                    else {
                        id = null;
                        Livewire.dispatch('selectedCliente', {
                            id
                        });
                    }
                },

            });

            //hacemos el scholl del body en la tabla
            $('.ts-dropdown-content').niceScroll({
                cursorwidth: 3,
                cursoropacitymin: 0.6,
                cursorcolor: 'rgb(0 0 0 / 50%)',
                cursorborder: 'none',
                cursorborderradius: 4,
                autohidemode: 'leave'
            });
        })
    </script>
@endscript
