<!-- BEGIN table Ventas-->
<div class="table-responsive mt-3">
    <table class="table table-hover text-nowrap small">
        @if ($cartContent != null)
            <thead class="bg-black">
                <tr>
                    <th width="50px"></th>
                    <th class="text-start">Producto</th>
                    <th class="text-center">Cantidad</th>
                    <th class="text-center">Precio Unit.</th>
                    <th class="text-center">Desc. Gs</th>
                    <th class="text-center">Desc. %</th>
                    <th style="width: 70px; min-width: 90px;"class="text-end">P. Unit. con desc.</th>
                    <th width="80px"class="text-end">Sub-total</th>
                    <th class="text-center">IVA</th>
                    <th width="15px"></th>
                </tr>
            </thead>
        @endif

        <tbody>
            @forelse($cartContent as $item)
                <tr class="align-middle">
                    <td class="text-center">
                        @if ($item['image'] == null)
                            <a href = "#">
                                <img alt="" class="object-fit-cover w-50px h-50px"
                                    src="{{ asset('storage/sin_imagen.png') }}" />
                            </a>
                        @else
                            <a href = "#">
                                <img alt="" class="object-fit-cover w-50px h-50px"
                                    src="{{ asset('storage/img/' . Auth::user()->tenant_id . '/' . 'productos/' . $item['image']) }}" />
                            </a>
                        @endif
                    </td>
                    <td class="text-start">
                        <div class="product-name h6">(cod.{{ $item['code'] }}) - <span
                                class="text-theme">{{ $item['name'] }}</span> <span
                                class="text-white-50">{{ $item['marca'] }} - {{ $item['presentation'] }}</span></div>
                    </td>

                    {{-- Input de cantidad --}}
                    <td style="width: 100px; min-width: 100px; padding: 0 5px; text-align: center;">
                        <div class="input-group flex-nowrap align-items-center" style="width: 100px; margin: 0 auto;">
                            <a class="me-2" href="javascript:void(0)"
                                wire:click="$dispatch('dec', { id: {{ $item['id'] }}, qty: {{ $item['qty'] }} })">
                                <i class="fa-solid fa-circle-minus fa-xl"></i></a>

                            <input type="text" id="rowQty{{ $item['id'] }}" onkeydown="filtrarTeclas()"
                                onclick="seleccionarTodo(this)" onkeyup="formatQty(this)"
                                x-mask:dynamic="$money($input, ',', '.')"
                                wire:change="$dispatch('replaceItemCant', { id: {{ $item['id'] }}, qty: {{ $item['qty'] }} })"
                                value="{{ number_format($item['qty'], 0, ',', '.') }}"
                                class="form-control form-control-sm text-end focus" autocomplete="off">

                            <a class="ms-2" href="javascript:void(0)"
                                wire:click="$dispatch('inc', { id: {{ $item['id'] }}, qty: {{ $item['qty'] }} })">
                                <i class="fa-solid fa-circle-plus fa-xl"></i></a>
                        </div>
                    </td>

                    {{-- Input de Precio Unitario --}}
                    <td style="width: 80px; min-width: 80px; padding: 0 5px; text-align: center;">
                        <div class="input-group input-group-sm" style="width: 80px; margin: 0 auto;">
                            <input type="text" id="rowPrice{{ $item['id'] }}" onkeydown="filtrarTeclas()"
                                onclick="seleccionarTodo(this)" onkeyup="formatQty(this)"
                                x-mask:dynamic="$money($input, ',', '.')"
                                wire:change="$dispatch('replacePrice', { id: {{ $item['id'] }}, price: {{ $item['price'] }} })"
                                value="{{ number_format($item['price'], 0, ',', '.') }}"
                                class="form-control text-end focus" autocomplete="off">
                        </div>
                    </td>

                    {{-- Input de Desc. Guaranies --}}
                    <td style="width: 80px; min-width: 80px; padding: 0 5px; text-align: center;">
                        <div class="input-group input-group-sm" style="width: 80px; margin: 0 auto;">
                            <input type="text" id="rowDescGs{{ $item['id'] }}" onkeydown="filtrarTeclas()"
                                onclick="seleccionarTodo(this)" onkeyup="formatQty(this)"
                                x-mask:dynamic="$money($input, ',', '.')"
                                wire:change="$dispatch('replaceDescGs', { id: {{ $item['id'] }}, descGs: {{ $item['descGs'] }} })"
                                value="{{ number_format($item['descGs'], 0, ',', '.') }}"
                                class="form-control text-end focus" autocomplete="off">
                        </div>
                    </td>

                    {{-- Input de Desc en porcentaje --}}
                    <td style="width: 70px; min-width: 70px; padding: 0 5px; text-align: center;">
                        <div class="input-group input-group-sm" style="width: 70px; margin: 0 auto;">
                            <input type="text" min="0" max="99.99" step="0.01"
                                id="rowDescX{{ $item['id'] }}" oninput="validarDecimales(this)"
                                onchange="formatearFinal(this)" onclick="seleccionarTodo(this)"
                                wire:change="$dispatch('replaceDescX', { id: {{ $item['id'] }}, descX: {{ $item['descX'] }} })"
                                value="{{ number_format($item['descX'], 2, '.') }}" class="form-control text-end focus"
                                autocomplete="off" placeholder="0.00">
                            <span class="input-group-text">%</span>

                        </div>
                    </td>

                    <td class="text-end">{{ number_format($item['precioConDesc'], 0, ',', '.') }}</td>

                    <td id="rowSubtotal{{ $item['id'] }}" class="text-end">
                        {{ number_format($item['subTotal'], 0, ',', '.') }}</td>

                    <td style="width: 60px; min-width: 60px; padding: 0 5px; text-align: center;">
                        <select class="form-select form-select-sm text-theme" style="width: 60px; margin: 0 auto;"
                            id="rowIva{{ $item['id'] }}"
                            wire:change="$dispatch('replaceItemIva', { product_id: {{ $item['id'] }}, iva: event.target.value })">
                            <option value="1" {{ $item['iva'] == 1 ? 'selected' : '' }}>10%</option>
                            <option value="2" {{ $item['iva'] == 2 ? 'selected' : '' }}>5%</option>
                            <option value="3" {{ $item['iva'] == 3 ? 'selected' : '' }}>Exc.</option>
                        </select>
                    </td>

                    <td class="text-center">
                        <i wire:click.prevent="deleteItem({{ $item['id'] }})" class="fa-solid fa-trash text-danger"
                            style="cursor: pointer"></i>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" style="height: 200px;"
                        class="fs-3 text-center bg-success-subtle d-flex align-items-center justify-content-center">
                        Ingrese los productos del Comprobante
                    </td>
                </tr>
            @endforelse
        </tbody>

        @if ($cartContent != null)
            <tfoot>
                <tr class="bg-success-subtle">
                    <td></td>
                    <td class="text-center">{{ $itemsCart }} items cargados</td>
                    <td class="text-end">{{ number_format($iva5x, 0, ',', '.') }}</td>
                    <td>IVA 5%</td>
                    <td class="text-end">{{ number_format($iva10x, 0, ',', '.') }}</td>
                    <td>IVA 10%</td>
                    <td></td>
                    <td class="text-theme fs-5 fw-bold text-end">{{ number_format($totales['total'], 0, ',', '.') }}
                    </td>
                    <td class="text-theme fs-5">TOTAL</td>
                    <td></td>
                </tr>
            </tfoot>
        @endif
    </table>
    <div class="text-center mt-4">
        @error('store')
            <div class="text-danger fs-4">
                {{ $message }}
            </div>
        @enderror
    </div>
</div>
<!-- END table Ventas-->


<script>
    function filtrarTeclas() {
        var tecla = event.key;
        if (['.', 'e', ',', '-'].includes(tecla))
            event.preventDefault();
    }

    function formatQty(input) {
        let stringQty = (input.value).split(".");
        let newQty = "";

        if (input.value != "") {
            for (i = 0; i < stringQty.length; i++) {
                newQty = parseInt(newQty + stringQty[i]);
            };
            input.value = new Intl.NumberFormat("de-DE").format(newQty);
        } else {
            input.value = null
        }
    }

    function validarDecimales(input) {
        let value = input.value;
        value = value.replace(/[^0-9.,]/g, ''); // Permite solo números, punto o coma
        value = value.replace(',', '.'); // Reemplaza comas por puntos (para manejo interno)

        if ((value.match(/\./g) || []).length > 1) { // Evita múltiples puntos decimales
            value = value.substring(0, value.lastIndexOf('.'));
        }

        const parts = value.split('.'); // Separa parte entera y decimal

        if (parts[0].length > 2) { // Valida parte entera (máx 2 dígitos)
            parts[0] = parts[0].slice(0, 2);
        }

        if (parts.length > 1) { // Valida parte decimal (máx 2 dígitos)
            parts[1] = parts[1].slice(0, 2);
        }

        input.value = parts.join('.');
    }

    function formatearFinal(input) {
        let num = parseFloat(input.value) || 0;
        num = Math.min(99.99, Math.max(0, num)); // Limita rango
        input.value = num.toFixed(2); // 2 decimales
    }

    function seleccionarTodo(input) {
        input.select();
        input.focus();
    }
</script>

@script
    <script>
        $wire.on('updateInputQty', (data) => {
            let el = document.querySelector(`[id="rowQty${data.id}"]`);
            let formatQty = new Intl.NumberFormat("de-DE").format(data.qty);
            el.value = formatQty;
        })

        $wire.on('updateInputDescGs', (data) => {
            let el = document.querySelector(`[id="rowDescGs${data.id}"]`);
            let formatPrice = new Intl.NumberFormat("de-DE").format(data.descGs);
            el.value = formatPrice;
        })

        $wire.on('updateInputDescX', (data) => {
            let el = document.querySelector(`[id="rowDescX${data.id}"]`);
            el.value = data.descX;
        })

        $wire.on('invalidQty', event => {
            document.getElementById('rowQty' + event.id).value = event.qty
        })

        $wire.on('invalidPrice', event => {
            document.getElementById('rowPrice' + event.id).value = event.price
        })

        $wire.on('invalidDesc', event => {
            document.getElementById('rowDescGs' + event.id).value = event.desc
        })

        $wire.on('invalidDescX', event => {
            document.getElementById('rowDescX' + event.id).value = event.descX
        })

        $wire.on('replaceItemCant', (data) => {
            enviarQty(data, valor = 0);
        })

        $wire.on('inc', (data) => {
            enviarQty(data, valor = 1);
        })

        $wire.on('dec', (data) => {
            enviarQty(data, valor = -1);
        })

        function enviarQty(data, valor) {
            let el = document.querySelector(`[id="rowQty${data.id}"]`); //seleccionamos el input
            let stringQty = (el.value).split("."); //dividimos el string despues de cada punto
            let newQty = "";

            for (i = 0; i < stringQty.length; i++) {
                newQty = parseInt(newQty + stringQty[i]); //convertimos en int el valor
            };

            newQty = newQty + valor;

            $wire.dispatch('replaceItemQty', {
                product_id: data.id,
                currentqty: data.qty,
                qty: newQty,
            })
        }

        $wire.on('replacePrice', (data) => { //reemplazamos el precio unitario en la sesion Card
            //------input Precio -------
            let price = document.querySelector(`[id="rowPrice${data.id}"]`); //seleccionamos el input
            let stringPrice = (price.value).split("."); //dividimos el string despues de cada punto
            let newPrice = "";

            for (i = 0; i < stringPrice.length; i++) {
                newPrice = parseInt(newPrice + stringPrice[i]); //convertimos en int el valor
            };

            //------input Descuento -------
            let desc = document.querySelector(`[id="rowDescGs${data.id}"]`);
            let stringDesc = (desc.value).split(".");
            let newDesc = "";

            for (i = 0; i < stringDesc.length; i++) {
                newDesc = parseInt(newDesc + stringDesc[i]); //convertimos en int el valor
            };


            if (newDesc >= newPrice) {
                document.getElementById('rowPrice' + data.id).value = data.price
                $wire.dispatch('atencion')
            } else {
                $wire.dispatch('replaceItemPrice', {
                    product_id: data.id,
                    currentprice: data.price,
                    price: newPrice,
                })
            }

        })

        $wire.on('replaceDescGs', (data) => { //reemplazamos el descuento en GS en la sesion Card
            //------input Precio -------
            let price = document.querySelector(`[id="rowPrice${data.id}"]`); //seleccionamos el input
            let stringPrice = (price.value).split("."); //dividimos el string despues de cada punto
            let newPrice = "";

            for (i = 0; i < stringPrice.length; i++) {
                newPrice = parseInt(newPrice + stringPrice[i]); //convertimos en int el valor
            };

            //------input Descuento -------
            let desc = document.querySelector(`[id="rowDescGs${data.id}"]`);
            let stringDesc = (desc.value).split(".");
            let newDesc = "";

            for (i = 0; i < stringDesc.length; i++) {
                newDesc = parseInt(newDesc + stringDesc[i]); //convertimos en int el valor
            };


            if (newDesc >= newPrice) {
                document.getElementById('rowDescGs' + data.id).value = data.descGs
                $wire.dispatch('atencion')
            } else {
                $wire.dispatch('replaceItemDescGs', {
                    product_id: data.id,
                    currentDesc: data.descGs,
                    descGs: newDesc,
                })
            }
        })

        $wire.on('replaceDescX', (data) => { //reemplazamos el descuento en X en la sesion Card
            let newDesc = document.querySelector(`[id="rowDescX${data.id}"]`).value;

            $wire.dispatch('replaceItemDescX', {
                product_id: data.id,
                currentDescX: data.descX,
                descX: newDesc,
            })

        })

        $wire.on('ventaOk', () => {
            window.dispatchEvent(new CustomEvent('swal:toast', {
                detail: {
                    text: 'Venta Registrada...',
                    background: 'success',
                }
            }));
        });

        $wire.on('atencion', () => {
            Swal.fire({
                background: 'rgb(255, 200, 220)', // Rosa claro
                title: 'ATENCIÓN...❗', // Icono de exclamación añadido
                text: 'El descuento debe ser inferior al precio unitario',
                showConfirmButton: false,
                timer: 3000,
                customClass: {
                    title: 'text-black',
                }
            });
        });



        $wire.on('precioActualizado', (data) => {
            Swal.fire({
                background: 'rgb(40, 200, 214',
                title: data.name,
                text: 'El precio se ha actualizado.  El monto total de su venta se actualizará al nuevo valor',
                confirmButtonText: 'OK',
                confirmButtonColor: 'navy',
                customClass: {
                    title: 'text-black',
                },
            }).then((result) => {
                if (result.isConfirmed) {
                    $wire.dispatch('replacePriceSubtotal', {
                        product_id: data.product_id,
                        currentPrice: data.currentPrice,
                    })
                }
            });
        });
    </script>
@endscript
