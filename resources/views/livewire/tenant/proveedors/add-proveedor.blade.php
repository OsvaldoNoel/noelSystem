<div>
    @include('livewire.tenant.proveedors.modals')
</div>

<script>
    document.addEventListener('livewire:init', () => {
       Livewire.on('addProveedor', () => {
           $('#addModal').modal('show');
       });
    });
</script>