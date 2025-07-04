<div>
    <livewire:tenant.reportes.compras.detalle-compras>
    <livewire:tenant.proveedors.add-proveedor>
        <div class="card mb-4">
            <div class="card-header fw-bold text-theme text-center">
                REPORTE DE COMPRAS
            </div>

            <div class="card-body bg-info-subtle p-0">
                <div class="row gx-4">
                    <div class="col-lg-8 col-xl-9">
                        <div class="card-body bg-info-subtle rounded-3 mt-3 mb-3 ms-3">

                            @include('livewire.tenant.reportes.compras.tableCompras')

                        </div>
                    </div>

                    <div class="col-lg-4 col-xl-3">
                        <div class="card-body bg-info-subtle rounded-3 mt-3 mb-3 me-3">

                            @include('livewire.tenant.reportes.compras.cardSelector')

                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>


@script
    <script>
        $wire.on('component-SelectedProveedor-ready', () => {
            let header = $wire.header
            if (header != null && header.proveedor_id != null) {
                $wire.proveedor_id = header.proveedor_id
                $wire.dispatch("proveedor_id", [header.proveedor_id])
            }
        })

        $wire.on('setHeader', () => {
            let fecha = document.getElementById('inputFecha').value
            let cond = document.getElementById('condCompra').value
            let tipo = document.getElementById('tipoComprobante').value
            let nroFac = document.getElementById('inputNroFac').value
            let nroTim = document.getElementById('inputTimbrado').value
            let nroOtros = document.getElementById('inputOtros').value
            let proveedorId = $wire.proveedor_id

            $wire.setHeaderData(fecha, cond, tipo, nroFac, nroTim, nroOtros,
                proveedorId) //funcion del traitPurchase 

        })

        $wire.on('updateInputs', () => {
            $('#inputFecha').datepicker('update', '');
            document.getElementById('condCompra').value = 1;
            document.getElementById('tipoComprobante').value = 1;
            document.getElementById('inputNroFac').value = null;
            document.getElementById('inputTimbrado').value = null;
            document.getElementById('inputOtros').value = null;

            $wire.dispatch('proveedor_id', {
                id: null
            });
        })

        // Inicialización de input Fecha
        $('#inputFecha').datepicker({
            language: 'es', // Establece el idioma en español
            format: 'dd - mm - yyyy', // Formato de fecha (opcional)
            autoclose: true, // Cierra el datepicker al seleccionar una fecha
            daysOfWeekHighlighted: "0", // Destaca domingos (0) y sábados (6)
            todayHighlight: true, // Resalta la fecha actual
        }).on('changeDate', function(e) {
            document.getElementById('inputFecha').value = e.format();
            $wire.borrarErrores('fechaFactura');
            $wire.dispatch('setHeader');
        });

        // Bloquear entrada manual (teclado, pegar, etc.)
        $('#inputFecha').on('keydown paste', function(e) {
            e.preventDefault();
            return false;
        });

        // Evitar que se borre con Backspace o Delete
        $(document).on('keydown', function(e) {
            if (e.key === 'Backspace' || e.key === 'Delete') {
                if ($('#inputFecha').is(':focus')) {
                    e.preventDefault();
                    return false;
                }
            }
        });

        // Inicialización de la máscara en los input
        $('#inputNroFac').inputmask("999-999-9999999");
        $('#inputTimbrado').inputmask("99999999");

        $wire.on('changeTipo', () => {
            $wire.borrarErrores('numerOtros');
            $wire.borrarErrores('numeroFactura');
            $wire.borrarErrores('timbrado');

            document.getElementById('inputNroFac').value = null;
            document.getElementById('inputTimbrado').value = null;
            document.getElementById('inputOtros').value = null;

            $wire.dispatch('setHeader');
        });

        $wire.on('validarInput', (event) => {
            const input = document.getElementById(event.id);
            const valor = input.value;
            const soloNumeros = valor.replace(/\D/g, '');
            const source = event.source || 'blur'; // 'blur' o 'enter'

            let long = 13;
            let title = "FORMATO INCOMPLETO";
            let msg = "Debe tener la siguiente estructura:";
            let format = "000-000-0000000";

            if (event.id == "inputTimbrado") {
                long = 8;
                title = "FORMATO INCOMPLETO";
                msg = "Se espera que tenga 8 digitos";
                format = "00000000";
            }

            if (event.id == "inputOtros") {
                long = 15;
                title = "ATENCION....!!!";
                msg = "No puede superar 15 caracteres";
                format = "";
            }

            if (soloNumeros.length === 0) {
                input.value = null;
                $wire.dispatch('setHeader');
                input.blur();
                return;
            }

            if (event.id == "inputOtros") {
                if (valor.length < 15) {
                    $wire.borrarErrores('numerOtros');
                    $wire.dispatch('setHeader');
                    input.blur();
                } else {
                    $wire.dispatch('toastError', {
                        title: title,
                        msg: msg,
                        format: format,
                        id: event.id,
                    })
                }
            } else {
                if (soloNumeros.length === long) {
                    if (source === 'enter') {
                        if (event.id == "inputNroFac") {
                            document.getElementById('inputTimbrado').focus();
                        } else {
                            input.blur();
                        }
                    } else {
                        if (event.id == "inputNroFac") {
                            $wire.borrarErrores('numeroFactura');
                        } else {
                            $wire.borrarErrores('timbrado');
                        }
                        $wire.dispatch('setHeader');
                    }
                } else {
                    $wire.dispatch('toastError', {
                        title: title,
                        msg: msg,
                        format: format,
                        id: event.id,
                    })
                }
            }
        });
    </script>
@endscript
