<div wire:ignore>
    <div class="form-group">
        <label for="select-aportante" class="mb-0 text-muted">Aportante</label>
        <div class="input-group">
            <select class="form-select text-theme" id="select-aportante" placeholder="Seleccione una opción"
                autocomplete="off">

            </select>
        </div>
    </div>
</div>

@script
    <script>
        let newName = new TomSelect("#select-aportante", {});

        $wire.$on('valorData', ($id) => {
            let options = JSON.parse($wire.options)
            newName.destroy();
            newName = new TomSelect("#select-aportante", {
                create: false,
                items: $id,
                options: options,
                valueField: 'id',
                labelField: 'name',
                searchField: 'name',
                hideSelected: true,
                sortField: {
                    field: "name",
                    direction: "asc",
                },
                itemClass: 'text-theme',
                onChange: function() {
                    let id = newName.items[0];
                    if (id != null) {
                        Livewire.dispatch('selectedAportante', {
                            id
                        });
                    } else {
                        id = null;
                        Livewire.dispatch('selectedAportante', {
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