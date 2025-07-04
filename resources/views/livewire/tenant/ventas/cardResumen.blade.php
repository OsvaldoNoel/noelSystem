<div class="card mb-4">
    <div class="card-header bg-success text-black fw-bold">
        RESUMEN
    </div>
    <div class="card-body bg-success-subtle">


        <table class="table table-borderless table-sm m-0">
            <tbody>
                <tr>
                    <td class="w-150px">Subtotal</td>
                    <td>{{ $itemsCart }} items</td>
                    <td class="text-end">${{ $totalCart }}</td>
                </tr>
                <tr>
                    <td>Descuento</td>
                    <td>Pago efectivo 5%</td>
                    <td class="text-end">$0</td>
                </tr>
                <tr>
                    <td>Costo de envio</td>
                    <td><u class="text-success fw-bold small">GRATIS</u> <small>(<span
                                class="text-decoration-line-through">$15.000</span>)</small></td>
                    <td class="text-end">$0</td>
                </tr>
                <tr>
                    <td class="pb-2" colspan="2"><b>Total</b></td>
                    <td class="text-end pb-2 text-decoration-underline"><b>$3670.80</b></td>
                </tr>
                <tr>
                    <td colspan="3">
                        <hr class="m-0">
                    </td>
                </tr>

            </tbody>
        </table>

        <div class="card-footer bg-none d-flex px-0">  
            <a wire:click.prevent="Store" wire:loading.attr='disabled'
                class="btn btn-theme ms-auto d-flex align-items-center">
                <iconify-icon icon="material-symbols-light:check"
                    class="fs-20px me-1 ms-n2 my-n2 d-inline-flex"></iconify-icon>
                <span wire:loading.remove wire:target='Store'> Registrar Venta</span>
                <span wire:loading wire:target='Store'> Registrando..</span>
            </a>
        </div>

    </div>
</div>
