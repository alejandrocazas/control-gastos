<x-app-layout>
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900 py-6 flex flex-col justify-center sm:py-12">
        <div class="relative py-3 sm:max-w-xl sm:mx-auto">
            <div class="absolute inset-0 bg-gradient-to-r from-blue-400 to-blue-600 shadow-lg transform -skew-y-6 sm:skew-y-0 sm:-rotate-6 sm:rounded-3xl"></div>
            <div class="relative px-4 py-10 bg-white dark:bg-gray-800 shadow-lg sm:rounded-3xl sm:p-20">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-6">Editar Gasto</h1>

                <form id="edit-gasto-form" action="{{ route('gastos.update', $gasto->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')
                    <div>
                        <label for="descripcion" class="block text-sm font-medium text-gray-700 dark:text-gray-100">Descripción</label>
                        <input type="text" id="descripcion" name="descripcion" value="{{ $gasto->descripcion }}" required
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 dark:placeholder-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    </div>
                    <div>
                        <label for="monto" class="block text-sm font-medium text-gray-700 dark:text-gray-100">Monto</label>
                        <input type="number" step="0.01" id="monto" name="monto" value="{{ $gasto->monto }}" required
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 dark:placeholder-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    </div>
                    <div>
                        <label for="fecha" class="block text-sm font-medium text-gray-700 dark:text-gray-100">Fecha</label>
                        <input type="date" id="fecha" name="fecha" value="{{ $gasto->fecha }}" required
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 dark:placeholder-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    </div>
                    <div class="flex space-x-4">
                        <button type="submit"  class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-save mr-2"></i> Actualizar
                        </button>
                        <a href="{{ route('gastos.index') }}" class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-arrow-left mr-2"></i> Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    function confirmUpdate() {
    Swal.fire({
        title: '¿Actualizar gasto?',
        html: 'Estás a punto de actualizar este gasto.<br><small style="color: #6b7280">Los cambios serán permanentes.</small>',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#2563eb',
        cancelButtonColor: '#dc2626',
        confirmButtonText: '<i class="fas fa-save mr-2"></i> Sí, actualizar',
        cancelButtonText: '<i class="fas fa-times mr-2"></i> Cancelar',
        reverseButtons: true,
        showClass: {
            popup: 'animate_animated animate_fadeInDown'
        },
        hideClass: {
            popup: 'animate_animated animate_fadeOutUp'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Procesando',
                html: 'Guardando los cambios...',
                timerProgressBar: true,
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                    setTimeout(() => {
                        document.getElementById('edit-gasto-form').submit();
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
            icon: 'success',
            title: '¡Actualizado!',
            text: '{{ session('success') }}',
            confirmButtonColor: '#2563eb',
            timer: 3000,
            timerProgressBar: true,
            showConfirmButton: false,
            position: 'top-end'
        });
    });
    @endif
    </script>
</x-app-layout>