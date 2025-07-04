<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('layouts.tenant.partial.include.head')
</head>

<body>
    @include('layouts.tenant.partial.include.loader')

    <!-- BEGIN #app -->
    <div id="app"
        class="app app-footer-fixed
        {{ Request::routeIs('reporteCompras') || Request::routeIs('carrito') || Request::routeIs('compras')
            ? ' app-sidebar-collapsed'
            : '' }}">

        <livewire:tenant.sidebar />

        <!-- BEGIN #content -->
        <div id="content" class="app-content">

            {{ $slot }}

        </div>
        <!-- END #content -->

        @include('layouts.tenant.partial.include.footer')
        @include('layouts.tenant.partial.include.scroll-top-btn')
    </div>
    <!-- END #app -->

    <div id="toast-container" class="position-fixed top-0 end-0 p-3" style="z-index: 1100"></div>
    @include('layouts.tenant.partial.include.scripts')
</body>

</html>

<script>
    document.addEventListener('livewire:init', () => {
        let value = document.getElementById("menuSuperior").value;
        if (value == 1) {
            document.getElementById("app").className =
                "app app-footer-fixed app-with-top-nav app-without-sidebar";
            let intro = document.getElementById('footer');
            intro.style.cssText = 'margin-left: 1.0625rem;';
            document.getElementById("menuSuperior").checked = true;
        }

        Livewire.on('menuSuperior', () => {
            let value = document.getElementById("menuSuperior").value;
            if (value == 0) {
                document.getElementById("app").className =
                    "app app-footer-fixed app-with-top-nav app-without-sidebar";
                let intro = document.getElementById('footer');
                intro.style.cssText = 'margin-left: 1.0625rem;';
                document.getElementById("menuSuperior").value = 1;
            } else {
                document.getElementById("app").className = "app app-footer-fixed";
                let intro = document.getElementById('footer');
                intro.style.cssText = 'margin-left: 14.0625rem;';
                document.getElementById("menuSuperior").value = 0;
            }
        });
        Livewire.on('showModal', (name) => {
            $('#dataModal' + name).modal('show');
        });
        Livewire.on('closeModal', () => {
            const openModals = document.querySelectorAll('.modal.show');
            openModals.forEach(modal => {
                let closeButton = modal.querySelector('[data-bs-dismiss="modal"]');

                if (!closeButton) {
                    closeButton = document.createElement('button');
                    closeButton.setAttribute('data-bs-dismiss', 'modal');
                    closeButton.style.position = 'absolute';
                    closeButton.style.opacity = '0';
                    closeButton.style.pointerEvents = 'none';
                    modal.appendChild(closeButton);
                }

                closeButton.focus();
                closeButton.blur();

                const bsModal = bootstrap.Modal.getInstance(modal);
                if (bsModal) {
                    bsModal.hide();
                }
            });
        });

        Livewire.on('registroExitoso', (msg) => {
            window.dispatchEvent(new CustomEvent('swal:toast', {
                detail: {
                    text: msg.text,
                    background: msg.bg,
                }
            }));
        });

        Livewire.on('toastError', (data) => {
            // Crear toast 
            const toast = document.createElement('div');
            toast.className = 'toast show soft-pink-toast';
            toast.setAttribute('role', 'alert');
            toast.setAttribute('aria-live', 'assertive');
            toast.setAttribute('aria-atomic', 'true');
            toast.innerHTML = `
                                <div class="toast-body text-center" style="padding: 1rem;">
                                    <div class="toast-title-alert">` + data.title + `</div>
                                    <hr class="toast-divider">
                                    <div class="toast-message-alert">` + data.msg + `<br>
                                    <span class="toast-format-example">` + data.format + `</span>
                                    </div>
                                </div>
                            `;

            document.body.appendChild(toast);
            document.getElementById(data.id).focus();

            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => toast.remove(), 150);
            }, 3000);
        })

        Livewire.on('msg-added', () => {
            window.dispatchEvent(new CustomEvent('swal:toast', {
                detail: {
                    text: 'Registro agregado...',
                    background: 'success',
                }
            }));
        });
        Livewire.on('msg-updated', () => {
            window.dispatchEvent(new CustomEvent('swal:toast', {
                detail: {
                    text: 'Actualizado correctamente...',
                    background: 'info',
                }
            }));
        });
        Livewire.on('msg-deleted', () => {
            window.dispatchEvent(new CustomEvent('swal:toast', {
                detail: {
                    text: 'Registro eliminado....',
                    background: 'danger',
                }
            }));
        });
    });
</script>
