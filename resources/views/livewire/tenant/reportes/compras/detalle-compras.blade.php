<div>
    <div wire:ignore.self class="modal fade" id="dataModalComprasDetalle" data-bs-backdrop="static" tabindex="-1"
        role="dialog" aria-modal="true" data-bs-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content position-relative border-0">
                <div class="position-absolute top-0 start-0 w-100 h-100 rounded-4 border border-3 border-info"
                    style="pointer-events: none;"></div>

                <div class="modal-body bg-info-subtle rounded-top-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="d-flex align-items-center">
                            <span class="me-2" style="min-width: 120px;"></span>
                            <span class="text-white fs-4">
                                {{ $tipoDocumento }}
                            </span>
                            <span>
                                {{ $condicionPago }}
                            </span>
                        </div>
                        <button type="button" class="btn-close text-info"
                            wire:click="$dispatch('closeModal')"></button>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-2">
                                <span class="fw-bold me-2" style="min-width: 120px;">Fecha:</span>
                                <span class="text-success">{{ $header['date'] ?? '' }}</span>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <span class="fw-bold me-2" style="min-width: 120px;">Proveedor:</span>
                                <span class="text-success">{{ $header['proveedor'] ?? '' }}</span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-2">
                                <span class="fw-bold me-2" style="min-width: 120px;">
                                    {{ $tipoDocumento == 'Factura LEGAL' ? 'Nro Factura:' : 'Nro Comprob:' }}
                                </span>
                                <span class="text-success">
                                    {{ $numeroDocumento }}
                                </span>
                            </div>
                            @if ($tipoDocumento == 'Factura LEGAL')
                                <div class="d-flex align-items-center mb-2">
                                    <span class="fw-bold me-2" style="min-width: 120px;">Timbrado:</span>
                                    <span class="text-success">{{ $header['timbrado'] ?? '' }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="modal-body pt-0 rounded-bottom-4">
                    <div class="table-responsive mt-3">
                        <table class="table table-hover text-nowrap small mb-0">
                            <thead class="bg-black">
                                <tr>
                                    <th class="text-start">Producto</th>
                                    <th class="text-end">Cantidad</th>
                                    <th class="text-end">Precio Unit.</th>

                                    @if ($totalDescuento != 0)
                                        <th class="text-end">Desc. Gs</th>
                                        <th class="text-end">Desc. %</th>
                                        <th class="text-end">P. con desc.</th>
                                    @endif

                                    <th class="text-end">Sub-total</th>
                                    @if ($tieneDosVariables)
                                        <th class="text-center">IVA</th>
                                    @endif
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($items as $item)
                                    <tr class="align-middle">
                                        <td><span class="text-theme">{{ $item->product->name }}</span>
                                            {{ ' -- ' . $item->product->marca->name . ' -- ' . $item->product->presentation->name }}
                                        </td>
                                        <td class="text-end">{{ number_format(floatval($item->quantity), 2, ',', '.') }}
                                        </td>
                                        <td class="text-end">
                                            {{ number_format(floatval($item->price_unit), 0, ',', '.') }}</td>

                                        @if ($totalDescuento != 0)
                                            <td class="text-end">
                                                {{ number_format(floatval($item->discount_amount), 0, ',', '.') }}</td>
                                            <td class="text-end">
                                                {{ number_format(floatval($item->discount_percent), 2, ',', '.') }}%
                                            </td>
                                            <td class="text-end">
                                                {{ number_format(floatval($item->price_unit_discounted), 0, ',', '.') }}
                                            </td>
                                        @endif

                                        <td class="text-end">
                                            {{ number_format(floatval($item->row_total), 0, ',', '.') }}</td>
                                        @if ($tieneDosVariables)
                                            <td class="text-center">
                                                {{ $item->iva == 1 ? '10%' : ($item->iva == 2 ? '5%' : 'Exc') }}
                                            </td>
                                        @endif
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No hay detalles disponibles</td>
                                    </tr>
                                @endforelse
                            </tbody>

                            <tfoot>
                                <tr class="bg-success-subtle">
                                    <td  colspan={{ $totalDescuento !=  0 ? "5" : "2" }} class="text-center">{{ $header['items'] ?? 0 }} items cargados
                                    </td>
                                    <td class="text-theme fs-5 text-end">TOTAL</td>
                                    <td class="text-theme fs-5 fw-bold text-end">
                                        {{ number_format(floatval($header['total'] ?? 0), 0, ',', '.') }}
                                    </td>
                                    @if ($tieneDosVariables)
                                        <td></td>
                                    @endif
                                </tr>
                                <tr>
                                    <td style="border: none;" colspan={{ $totalDescuento != 0 ? "5" : "2" }}></td>
                                    <td style="border: none;" class="text-end">IVA 10%</td>
                                    <td style="border: none;" class="text-end">
                                        {{ number_format($tieneIva10, 0, ',', '.') }}</td>
                                    @if ($tieneDosVariables)
                                        <td style="border: none;"></td>
                                    @endif
                                </tr>
                                @if ($tieneIva5)
                                    <tr>
                                        <td style="border: none;" colspan={{ $totalDescuento != 0 ? "5" : "2" }}></td>
                                        <td style="border: none;" class="text-end">IVA 5%</td>
                                        <td style="border: none;" class="text-end">
                                            {{ number_format($tieneIva5, 0, ',', '.') }}</td>
                                        @if ($tieneDosVariables)
                                            <td style="border: none;"></td>
                                        @endif
                                    </tr>
                                @endif

                                @if ($tieneExenta)
                                    <tr>
                                        <td style="border: none;" colspan={{ $totalDescuento != 0 ? "5" : "2" }} ></td>
                                        <td style="border: none;" class="text-end">Exc.</td>
                                        <td style="border: none;" class="text-end">
                                            {{ number_format($tieneExenta, 0, ',', '.') }}</td>
                                        @if ($tieneDosVariables)
                                            <td style="border: none;"></td>
                                        @endif
                                    </tr>
                                @endif
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@script
    <script>
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                $wire.dispatch('closeModal');
            }
        });
    </script>
@endscript
