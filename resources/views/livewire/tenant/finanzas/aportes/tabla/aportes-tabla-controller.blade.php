<div>
    @include('livewire.tenant.finanzas.aportes.tabla.modals')

    <div id="sportes">
        <div class="card">
            <div class="card-header with-btn">
                APORTES
                <div class="card-header-btn">
                    <a href="#" data-toggle="card-collapse" class="btn"><iconify-icon
                            icon="material-symbols-light:stat-minus-1"></iconify-icon></a>
                    <a href="#" data-toggle="card-expand" class="btn"><iconify-icon
                            icon="material-symbols-light:fullscreen"></iconify-icon></a>
                    <a href="#" data-toggle="card-remove" class="btn"><iconify-icon
                            icon="material-symbols-light:close-rounded"></iconify-icon></a>
                </div>
            </div>
            <div class="card-body">
                @include('livewire.tenant.finanzas.aportes.tabla.tableAportes')
            </div>
        </div>
    </div>
</div>