<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('layouts.landlord.partial.include.head')
    @stack('styles') <!-- Aquí se cargarán los estilos específicos --> 
</head>

<body>
    @include('layouts.landlord.partial.include.loader')

    <!-- BEGIN #app -->
    <div id="app" class="app app-footer-fixed">

        <livewire:landlord.sidebar />

        <!-- BEGIN #content -->
        <div id="content" class="app-content">

            {{ $slot }}

        </div>
        <!-- END #content -->

        @include('layouts.landlord.partial.include.footer')
        @include('layouts.landlord.partial.include.scroll-top-btn')
    </div>
    <!-- END #app -->

    @include('layouts.landlord.partial.include.scripts')

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
                                    <div class="toast-message-alert">` + data.msg + `</div>
                                </div>
                            `;

            document.body.appendChild(toast);

            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => toast.remove(), 150);
            }, 10000);
        })

    });
</script>

<script>
    function toast(mensaje, tipo = 'success') {
        // creamos el contenedor si no existe
        let toastContainer = document.getElementById('toastContainer');
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.id = 'toastContainer';
            toastContainer.className = 'position-fixed bottom-0 end-0 p-3';
            toastContainer.style.zIndex = '11';
            document.body.appendChild(toastContainer);
        }

        // creamos el elemento html toast
        const toastHTML = `
            <div class="toast" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header bg-${tipo} text-white">
                    <strong class="me-auto">Notificación</strong>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    ${mensaje}
                </div>
            </div>
        `;

        // agregamos el toast al contenedor
        toastContainer.insertAdjacentHTML('beforeend', toastHTML);

        // inicializamos y mostramos el toast
        const toastElement = toastContainer.lastElementChild;
        const toast = new bootstrap.Toast(toastElement);
        toast.show();

        // eliminamos el toast del DOM después de ocultarlo
        toastElement.addEventListener('hidden.bs.toast', () => {
            toastElement.remove();
        });
    }
</script>



<script>
    $(document).ready(function() {
        localStorage.setItem("color", 'color-5');
        localStorage.setItem("primary", '#884A39');
        localStorage.setItem("secondary", '#C38154');
    })
</script>
