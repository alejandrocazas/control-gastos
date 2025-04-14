<x-app-layout>
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900 py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6">Generar Reporte</h1>

                    <form id="reporteForm" action="{{ route('reportes.generar') }}" method="POST" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="tipo_reporte" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Periodo del Reporte</label>
                                <select id="tipo_reporte" name="tipo_reporte" required
                                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                    <option value="dias">Diario</option>
                                    <option value="semanas">Semanal</option>
                                    <option value="meses">Mensual</option>
                                </select>
                            </div>

                            <div>
                                <label for="tipo_operacion" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tipo de Operación</label>
                                <select id="tipo_operacion" name="tipo_operacion" required
                                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                    <option value="gastos">Solo Gastos</option>
                                    <option value="ingresos">Solo Ingresos</option>
                                    <option value="ambos">Ambos</option>
                                </select>
                            </div>

                            <div>
                                <label for="fecha_inicio" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Fecha Inicio</label>
                                <input type="date" id="fecha_inicio" name="fecha_inicio" required
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                            </div>

                            <div>
                                <label for="fecha_fin" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Fecha Fin</label>
                                <input type="date" id="fecha_fin" name="fecha_fin" required
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-800">
                                <i class="fas fa-file-pdf mr-2"></i> Generar Reporte
                            </button>
                            <button 
                                type="button"
                                id="exportPdfBtn"
                                class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 dark:focus:ring-offset-gray-800 ml-2"
                            >
                                <i class="fas fa-file-pdf mr-2"></i> Exportar PDF
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const exportPdfBtn = document.getElementById('exportPdfBtn');
    
    if (exportPdfBtn) {
        exportPdfBtn.addEventListener('click', function() {
            // Obtener los valores directamente de los campos
            const tipoReporte = document.querySelector('[name="tipo_reporte"]')?.value;
            const tipoOperacion = document.querySelector('[name="tipo_operacion"]')?.value;
            const fechaInicio = document.querySelector('[name="fecha_inicio"]')?.value;
            const fechaFin = document.querySelector('[name="fecha_fin"]')?.value;
            
            if (!tipoReporte || !tipoOperacion || !fechaInicio || !fechaFin) {
                alert('Por favor complete todos los campos');
                return;
            }
            
            // Construir URL para PDF
            const url = new URL('{{ route("reportes.exportar-pdf") }}');
            url.searchParams.append('tipo_reporte', tipoReporte);
            url.searchParams.append('tipo_operacion', tipoOperacion);
            url.searchParams.append('fecha_inicio', fechaInicio);
            url.searchParams.append('fecha_fin', fechaFin);
            
            // Descargar directamente
            window.location.href = url.toString();
        });
    } else {
        console.error('Botón de exportar no encontrado');
    }
});
</script>
@endpush
</x-app-layout>