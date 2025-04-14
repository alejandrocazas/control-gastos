<x-app-layout>
    <div class="min-h-screen bg-gray-100  dark:bg-gray-900 py-6 flex flex-col justify-center sm:py-12">
        <div class="relative py-3 sm:max-w-xl sm:mx-auto">
            <div class="absolute inset-0 bg-gradient-to-r  from-orange-400 to-red-600 shadow-lg transform -skew-y-6 sm:skew-y-0 sm:-rotate-6 sm:rounded-3xl"></div>
            <div class="relative px-4 py-10 bg-white dark:bg-gray-800 shadow-lg sm:rounded-3xl sm:p-20">
                <h1 class="text-3xl font-bold text-gray-900  dark:text-gray-100 mb-6">Agregar Ingreso</h1>

                @if (session('info'))
                <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{session('info')}}</span>
                </div>
                @endif

                <form id="ingreso-form" action="{{ route('ingresos.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <div>
                        <label for="descripcion" class="block text-sm font-medium text-gray-700 dark:text-gray-100">Descripción</label>
                        <input type="text" id="descripcion" name="descripcion" required
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 dark:placeholder-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    </div>
                    <div>
                        <label for="monto" class="block text-sm font-medium text-gray-700 dark:text-gray-100">Monto</label>
                        <input type="number" step="0.01" id="monto" name="monto" required
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 dark:placeholder-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    </div>
                    <div>
                        <label for="fecha" class="block text-sm font-medium text-gray-700 dark:text-gray-100">Fecha</label>
                        <input type="date" id="fecha" name="fecha" required
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 dark:placeholder-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    </div>
                    <div class="flex space-x-4">
                        <button type="button" onclick="confirmSubmit()" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-save mr-2"></i> Guardar
                        </button>
                        <a href="{{ route('ingresos.index') }}" class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-arrow-left mr-2"></i> Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <script>
    function confirmSubmit() {
        Swal.fire({
            title: '¿Registrar nuevo ingreso?',
            html: '<div class="animate_animated animate_bounceIn">¿Estás seguro de guardar este ingreso?</div>',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#2563eb',
            cancelButtonColor: '#d33',
            confirmButtonText: '<i class="fas fa-check mr-2"></i> Sí, guardar',
            cancelButtonText: '<i class="fas fa-times mr-2"></i> Cancelar',
            background: '#f8fafc',
            backdrop: `
                rgba(0,0,123,0.4)
                url("/images/nyan-cat.gif")
                left top
                no-repeat
            `,
            showClass: {
                popup: 'animate_animated animate_fadeInDown'
            },
            hideClass: {
                popup: 'animate_animated animate_fadeOutUp'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Mostrar animación de carga
                Swal.fire({
                    title: 'Procesando',
                    html: '<div class="animate_animated animateflash animate_infinite">Guardando ingreso...</div>',
                    timerProgressBar: true,
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                        // Enviar formulario después de breve pausa para ver la animación
                        setTimeout(() => {
                            document.getElementById('ingreso-form').submit();
                        }, 500);
                    }
                });
            }
        });
    }

    // Mostrar mensaje de éxito si existe en la sesión
    @if(session('success'))
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            title: '¡Éxito!',
            text: '{{ session('success') }}',
            icon: 'success',
            confirmButtonColor: '#2563eb',
            showClass: {
                popup: 'animate_animated animate_bounceIn'
            },
            timer: 3000,
            timerProgressBar: true
        });
    });
    @endif
    </script>
</x-app-layout>