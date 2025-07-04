<div>
    @include('livewire.tenant.clientes.modals')
</div>

<script>
    document.addEventListener('livewire:init', () => {
       Livewire.on('addCliente', () => {
           $('#addModal').modal('show');
       });
    });
</script>
