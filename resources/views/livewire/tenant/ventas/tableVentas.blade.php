<!-- BEGIN table Ventas-->
<div class="table-responsive mt-3">
    <table class="table table-hover text-nowrap small mb-4">
        <thead class="bg-black">
            <tr>
                <th width="50px"></th>
                <th class="text-start">Producto</th>
                <th width="150px" class="text-center">Cantidad</th>
                <th width="80px"class="text-end">Precio</th>
                <th width="80px"class="text-end">Sub-total</th>
                <th width="15px"></th>
            </tr>
        </thead>
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
                    <td>
                        <div class="input-group flex-nowrap align-items-center">
                            <a class="me-2" href="javascript:void(0)"
                                wire:click="$dispatch('dec', { id: {{ $item['id'] }}, qty: {{ $item['qty'] }} })">
                                <i class="fa-solid fa-circle-minus fa-xl"></i></a>

                            <input type="text" id="row{{ $item['id'] }}" onkeydown="filtrarTeclas()"
                                onkeyup="format({{ $item['id'] }})" x-mask:dynamic="$money($input, ',', '.')"
                                wire:change="$dispatch('replaceItemCant', { id: {{ $item['id'] }}, qty: {{ $item['qty'] }} })"
                                value="{{ number_format($item['qty'], 0, ',', '.') }}"
                                class="form-control text-end focus" autocomplete="off">

                            <a class="ms-2" href="javascript:void(0)"
                                wire:click="$dispatch('inc', { id: {{ $item['id'] }}, qty: {{ $item['qty'] }} })">
                                <i class="fa-solid fa-circle-plus fa-xl"></i></a>
                        </div>
                    </td>
                    <td class="text-end">{{ number_format($item['price'], 0, ',', '.') }}</td>
                    <td class="text-end">{{ number_format($item['total'], 0, ',', '.') }}</td>
                    <td>
                        <i wire:click.prevent="deleteItem({{ $item['id'] }})" class="fa-solid fa-trash text-danger"
                            style="cursor: pointer"></i>
                    </td>
                </tr>
            @empty
                <tr>
                    <td></td>
                    <td class="w-100 text-center">Ingrese Productos para la venta</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="bg-success-subtle">
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td class="text-theme fw-bold text-end">{{ number_format($totalCart, 0, ',', '.') }}</td>
                <td></td>

            </tr>
        </tfoot>
    </table>
</div>
<!-- END table Ventas-->

<script>
    document.addEventListener('livewire:init', () => {
        try {
            onScan.attachTo(document, {
                onScan: function(code) { // Alternative to document.addEventListener('scan') 
                    console.log('Scanned: ' + code);
                    Livewire.dispatch('scanCode', {
                        barcode: code
                    })
                },

                suffixKeyCodes: [13], // enter-key expected at the end of a scan
                reactToPaste: true, // Compatibility to built-in scanners in paste-mode (as opposed to keyboard-mode)

                onScanError: function(e) {
                    //console.log(e);
                }
            });
            console.log('Scanner Ready..!');
        } catch (e) {
            console.log('Error de Lectura: ', e);
        }
    })
</script>


<script>
    function filtrarTeclas() {
        var tecla = event.key;
        if (['.', 'e', ',', '-'].includes(tecla))
            event.preventDefault();
    }

    function format(id) {
        let el = document.querySelector(`[id="row${id}"]`);
        let stringQty = (el.value).split(".");
        let newQty = "";

        if (el.value != "") {
            for (i = 0; i < stringQty.length; i++) {
                newQty = parseInt(newQty + stringQty[i]);
            };
            el.value = new Intl.NumberFormat("de-DE").format(newQty);
        } else {
            el.value = null
        }
    }
</script>

@script
    <script>
        $wire.on('updateInput', (data) => {
            let el = document.querySelector(`[id="row${data.id}"]`);
            let formatQty = new Intl.NumberFormat("de-DE").format(data.qty);
            el.value = formatQty;

            console.log(formatQty)
        })

        $wire.on('invalidqty', event => {
            document.getElementById('row' + event.id).value = event.qty
        })

        $wire.on('replaceItemCant', (data) => {
            dispatch(data, valor = 0);
        })

        $wire.on('inc', (data) => {
            dispatch(data, valor = 1);
        })

        $wire.on('dec', (data) => {
            dispatch(data, valor = -1);
        })

        function dispatch(data, valor) {
            let el = document.querySelector(`[id="row${data.id}"]`); //seleccionamos el input
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

        // $wire.on('ventaOk', () => {
        //     window.dispatchEvent(new CustomEvent('swal:toast', {
        //         detail: {
        //             text: 'Venta Registrada...',
        //             background: 'success',
        //         }
        //     }));
        // });

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
                    $wire.dispatch('replacePrice', { 
                        product_id: data.product_id,
                        currentPrice: data.currentPrice, 
                    })
                }
            });
        });
    </script>
@endscript
