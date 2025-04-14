<x-app-layout>
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900 py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-6">Listado de Gastos</h1>

                    <!-- Botón para agregar gasto -->
                    <a href="{{ route('gastos.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 mb-4">
                        <i class="fas fa-plus mr-2"></i> Agregar Gasto
                    </a>

                    <!-- Mensajes de éxito o error -->
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    <!-- Tabla de gastos -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white dark:bg-gray-700">
                            <thead class="bg-gray-800 dark:bg-gray-900 text-white">
                                <tr>
                                    <th class="px-4 py-2">Descripción</th>
                                    <th class="px-4 py-2">Monto</th>
                                    <th class="px-4 py-2">Fecha</th>
                                    <th class="px-4 py-2">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700 dark:text-gray-300">
                                @foreach ($gastos as $gasto)
                                <tr data-id="{{$gasto->id}}" class="hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <td class="border px-4 py-2">{{ $gasto->descripcion }}</td>
                                    <td class="border px-4 py-2">Bs{{ number_format($gasto->monto, 2) }}</td>
                                    <td class="border px-4 py-2">{{ $gasto->fecha }}</td>
                                    <td class="border px-4 py-2">
                                        <a href="{{ route('gastos.edit', $gasto->id) }}" 
                                            onclick="return confirmEdit(event, {{ $gasto->id }});"
                                            class="inline-flex items-center px-3 py-1 bg-yellow-500 border border-transparent rounded-md font-semibold text-white hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                                            <i class="fas fa-pen-to-square mr-1"></i> Editar
                                        </a>
                                        <form id="delete-form-{{ $gasto->id }}" action="{{ route('gastos.destroy', $gasto->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="confirmDelete('delete-form-{{ $gasto->id }}')" class="inline-flex items-center px-3 py-1 bg-red-600 border border-transparent rounded-md font-semibold text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 ml-2">
                                                <i class="fas fa-trash mr-1"></i> Eliminar
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{$gastos->links()}}
                    </div>

                </div>
            </div>
        </div>
    </div>
    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: '¿Eliminar gasto?',
                text: "¡Esta acción no se puede deshacer!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Construye la URL correctamente
                    const url = {{ route('gastos.destroy', '') }}/${id};
                    
                    fetch(url, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Error en la respuesta del servidor');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if(data.success) {
                            // Elimina la fila visualmente
                            document.querySelector(tr[data-id="${id}"]).remove();
                            
                            Swal.fire(
                                '¡Eliminado!',
                                data.message,
                                'success'
                            );
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire(
                            'Error',
                            'No se pudo eliminar el gasto',
                            'error'
                        );
                    });
                }
            });
        }
        </script>
        <script>
            function confirmEdit(event, id) {
                event.preventDefault(); // Previene la navegación inmediata
                
                Swal.fire({
                    title: '¿Editar este gasto?',
                    text: "Serás redirigido al formulario de edición",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, continuar',
                    cancelButtonText: 'Cancelar',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Redirige solo si confirma
                        window.location.href = "{{ route('gastos.edit', ':id') }}".replace(':id', id);
                    }
                });
                
                return false; // Importante para prevenir el comportamiento por defecto
            }
            </script>
            @if(session('swal'))
            <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: '{{ session('swal.icon') }}',
                    title: '{{ session('swal.title') }}',
                    text: '{{ session('swal.text') }}',
                    confirmButtonColor: '#2563eb',
                });
            });
            </script>
            @endif
            @if(session('success'))
<script>
document.addEventListener('DOMContentLoaded', function() {
    Swal.fire({
        icon: 'success',
        title: '¡Éxito!',
        text: '{{ session('success') }}',
        confirmButtonColor: '#3085d6',
    });
});
</script>
@endif
</x-app-layout>