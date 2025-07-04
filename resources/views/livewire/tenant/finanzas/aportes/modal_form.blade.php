<form wire:submit="save">

    <div class="row">

        {{-- @if ($operacion == 1)
            <div class="col-6">
                <label class="mb-0 text-muted">Valor a devolver</label>
            @else --}}
        <div class="">
            {{-- @endif --}}

            <div class="input-group">
                <span class="input-group-text" style="width: 80px" id="monto">Monto</span>
                <input wire:model="monto" wire:keydown="convertInteger" type="text"
                    x-mask:dynamic="$money($input, ',', '.')" onkeydown="filtrarTeclas()"
                    class="form-control text-end text-theme" placeholder="0" id="monto" autocomplete="off">
            </div>
            @error('monto')
                <span class="error text-danger">{{ $message }}</span>
            @enderror
        </div>

        {{-- @if ($operacion == 1)
        <div class="mb-4 col-6 mt-4">
        @else --}}
        <div class="mb-4 mt-4">
            {{-- @endif --}}

            <livewire:tenant.finanzas.aportantes.selected-aportantes>
                @error('aportante_id')
                    <span class="error text-danger">{{ $message }}</span>
                @enderror
        </div>
    </div>

    <!-- inicio #radios -->
    @if ($operacion == 0)
        <div class="text-muted  mb-2"><b class="fw-bold">Destino</b></div>
    @else
        <div class="text-muted  mb-2"><b class="fw-bold">Retirado de:</b></div>
    @endif

    <div class="form-group mb-4">
        <div class="form-check form-check-inline">
            <input wire:click="func_Destino(0)" class="form-check-input" name="destino" type="radio" id="destino1">
            <label class="form-check-label pointer" for="destino1">Caja TESORERIA</label>
        </div>
        <div class="form-check form-check-inline">
            <input wire:click="func_Destino(null)" class="form-check-input" name="destino" type="radio"
                id="destino2">
            <label class="form-check-label pointer" for="destino2">Entidad</label>
        </div>
    </div>
    <!-- fin #radios -->

    <div class="input-group">
        <input type="text" class="form-control flatpickr" placeholder="Seleccione una fecha.."
            {{ $destino === 0 ? 'disabled' : '' }}>
        <span class="input-group-text">
            <a class="input-button" title="mostrar" data-toggle>
                <Span class="fas fa-calendar"></Span>
            </a>
        </span>
    </div>
    @error('date')
        <span class="error text-danger">{{ $message }}</span>
    @enderror


    @if ($destino != 0 || $destino === null)
        <div class="col mb-4 mt-4">
            <livewire:tenant.config.bancos.selected-banco>
                @error('destino')
                    <span class="error text-danger">{{ $message }}</span>
                @enderror
        </div>
    @endif

    <div class="form-group mt-3">
        @if ($operacion == 0)
            <label class="mb-0 text-muted" for="detail">Detalle del aporte</label>
        @else
            <label class="mb-0 text-muted" for="detail">Detalle del retiro</label>
        @endif

        <div class="input-group">
            <input wire:model="detail" type="text" class="form-control text-theme" id="detail"
                placeholder="Alguna referencia..?" autocomplete="off">
        </div>
        @error('detail')
            <span class="error text-danger">{{ $message }}</span>
        @enderror
    </div>

</form>

<script>
    function filtrarTeclas() {
        var tecla = event.key;
        if (['.', 'e', ',', '-', '+'].includes(tecla))
            event.preventDefault()
    }
</script>

@script
    <script>
        $wire.on('selectedCaja', () => {
            $(".flatpickr").flatpickr({
                enableTime: true,
                time_24hr: true,
                dateFormat: "d-m-Y H:i",
                locale: "es",
                defaultDate: new Date(),

                onReady: function(selectedDates, dateStr, instance) {
                    $wire.date = dateStr
                },

                onClose: function(selectedDates, dateStr, instance) {
                    $wire.date = dateStr
                },
            });
        });
    </script>
@endscript
