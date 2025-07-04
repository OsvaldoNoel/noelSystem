<div wire:ignore>
    <div class="form-group">
        <label for="select-banco" class="mb-0 text-muted" >Entidad</label>
        <div class="input-group">
            <select class="form-select text-theme" id="select-banco" placeholder="Seleccione una opción" autocomplete="off">
                 
            </select>   
        </div> 
    </div> 
</div> 

@script
<script>  
    let newName = new TomSelect("#select-banco",{}); 
    
    $wire.$on('valorData', ($id) => { 
        let options = JSON.parse($wire.options) 
        newName.destroy();   
        newName = new TomSelect("#select-banco",{
            create: false,
            items: $id,
            options: options,
            valueField: 'id',
            labelField: 'nombre',
            searchField: 'nombre',
            hideSelected: true,
            sortField: {
                field: "nombre",
                direction: "asc", 
            }, 
	        itemClass: 'text-theme', 
            onChange: function() {
                let id = newName.items[0];
                if(id != null){
                    Livewire.dispatch('selectedBanco', {id}); 
                }else{
                    id = null;
                    Livewire.dispatch('selectedBanco', {id}); 
                } 
            },
             
        });

        //hacemos el scholl del body en la tabla
        $('.ts-dropdown-content').niceScroll({
                    cursorwidth:3,
                    cursoropacitymin:0.6,
                    cursorcolor: 'rgb(0 0 0 / 50%)',
                    cursorborder:'none',
                    cursorborderradius:4,
                    autohidemode:'leave'
                }); 
    })      
    
</script>
@endscript