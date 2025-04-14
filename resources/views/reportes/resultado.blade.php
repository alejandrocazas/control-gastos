<x-app-layout>
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900 py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex justify-between items-center mb-6">
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Reporte {{ $data['tipo_reporte'] }}</h1>
                        <button onclick="window.print()" class="inline-flex items-center px-4 py-2 bg-gray-600 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-white hover:bg-gray-700 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 dark:focus:ring-gray-400">
                            <i class="fas fa-print mr-2"></i> Imprimir
                        </button>
                    </div>

                    <div class="mb-8 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-blue-50 dark:bg-blue-900/30 p-4 rounded-lg">
                            <h3 class="text-lg font-medium text-blue-800 dark:text-blue-200">Total Ingresos</h3>
                            <p class="text-2xl font-bold text-blue-900 dark:text-blue-100">Bs. {{ number_format($data['total_ingresos'], 2) }}</p>
                        </div>
                        <div class="bg-red-50 dark:bg-red-900/30 p-4 rounded-lg">
                            <h3 class="text-lg font-medium text-red-800 dark:text-red-200">Total Gastos</h3>
                            <p class="text-2xl font-bold text-red-900 dark:text-red-100">Bs. {{ number_format($data['total_gastos'], 2) }}</p>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        {{ $data['tipo_reporte'] == 'dias' ? 'Fecha' : 'Periodo' }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Ingresos</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Gastos</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Balance</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($data['detalle'] as $item)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">
                                        {{ $item['fecha'] ?? $item['periodo'] }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-green-600 dark:text-green-400">Bs. {{ number_format($item['ingresos'], 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-red-600 dark:text-red-400">Bs. {{ number_format($item['gastos'], 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900 dark:text-gray-100">
                                        Bs. {{ number_format($item['ingresos'] - $item['gastos'], 2) }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Sección de gastos detallados -->
                    @if(isset($data['gastos_detallados']) && !empty($data['gastos_detallados']))
                    <div class="mt-8">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-4">Detalle de Gastos</h2>
                        @foreach($data['gastos_detallados'] as $periodo => $gastos)
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-200 mb-2">{{ $periodo }}</h3>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Descripción</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Fecha</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Monto</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach($gastos as $gasto)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                            <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ $gasto['descripcion'] }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ \Carbon\Carbon::parse($gasto['fecha'])->format('d/m/Y') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-red-600 dark:text-red-400">Bs. {{ number_format($gasto['monto'], 2) }}</td>
                                        </tr>
                                        @endforeach
                                        <tr class="bg-gray-50 dark:bg-gray-700 font-bold">
                                            <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100" colspan="2">Total</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-red-600 dark:text-red-400">Bs. {{ number_format(collect($gastos)->sum('monto'), 2) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif

                    <div class="mt-6 flex justify-end">
                        <a href="{{ route('reportes.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-white hover:bg-gray-700 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 dark:focus:ring-gray-400">
                            <i class="fas fa-arrow-left mr-2"></i> Volver
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="mt-8 bg-white dark:bg-gray-800 p-6 max-w-7xl mx-auto sm:px-6 lg:px-8 rounded-lg shadow-sm">
        <canvas id="reporteChart" height="100" class="dark:text-gray-100"></canvas>
    </div>
</x-app-layout>