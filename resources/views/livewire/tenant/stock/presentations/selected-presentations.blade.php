<div wire:ignore>
    <div class="form-group">
        <label for="select-presentation" class="mb-0 text-muted" >Presentación</label>
        <div class="input-group">
            <span class="input-group-text text-success"><Span class="fa fa-edit"></Span></span>
            <select class="form-select text-theme" id="select-presentation" placeholder="Seleccione una opción" autocomplete="off">
                 
            </select>   
        </div> 
    </div> 
</div> 

@script
<script>  
    let newName = new TomSelect("#select-presentation",{}); 
    
    $wire.$on('valorData', ($id) => { 
        let options = JSON.parse($wire.options) 
        newName.destroy();   
        newName = new TomSelect("#select-presentation",{
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
                if(id != null){
                    Livewire.dispatch('selectedPresentation', {id}); 
                }else{
                    id = null;
                    Livewire.dispatch('selectedPresentation', {id}); 
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


