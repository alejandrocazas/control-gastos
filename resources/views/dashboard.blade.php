<!DOCTYPE html>
<html lang="es" x-data="{ darkMode: JSON.parse(localStorage.getItem('darkMode')) || false }" :class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Control de Gastos</title>
    <script src="//unpkg.com/alpinejs" defer></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        #gastosMensualesChart {
            width: 100% !important;
            height: 400px !important;
        }
    </style>
</head>
<body class="bg-gray-100 dark:bg-gray-900">
    <!-- Navbar -->
    <nav class="bg-white dark:bg-gray-800 shadow-lg">
        <div class="max-w-6xl mx-auto px-4">
            <div class="flex justify-between">
                <div class="flex space-x-7">
                    <!-- Logo -->
                    <div>
                        <a href="{{ route('dashboard') }}" class="flex items-center py-4 px-2">
                            <span class="font-bold text-blue-800 dark:text-blue-300 text-lg">Control de Gastos</span>
                        </a>
                    </div>
                    <!-- Menú Principal -->
                    <div class="hidden md:flex items-center space-x-1">
                        <a href="{{ route('gastos.index') }}" class="py-4 px-2 text-blue-500 dark:text-blue-300 font-semibold hover:text-blue-500 transition duration-300">
                            Ver Gastos
                        </a>
                        <a href="{{ route('ingresos.index') }}" class="py-4 px-2 text-blue-500 dark:text-blue-300 font-semibold hover:text-blue-500 transition duration-300">
                            Ver Ingresos
                        </a>
                    </div>
                </div>
                <!-- Menú de Usuario y Modo Oscuro -->
                <div class="hidden md:flex items-center space-x-3">
                    <!-- Botón de Modo Oscuro -->
                    <button @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode)" class="p-2 text-gray-700 dark:text-gray-300 hover:text-blue-500 dark:hover:text-blue-300 transition duration-300">
                        <svg x-show="!darkMode" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                        </svg>
                        <svg x-show="darkMode" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </button>
                    <!-- Menú de Usuario -->
                    <div x-data="{ profileMenuOpen:false }" class="relative">
                        <button @click="profileMenuOpen = !profileMenuOpen" class="flex items-center space-x-2 p-2 text-gray-700 dark:text-gray-300 hover:text-blue-500 dark:hover:text-blue-300 transition duration-300">
                            <span>{{ Auth::user()->name }}</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <!-- Submenú de Perfil -->
                        <div x-show="profileMenuOpen" @click.away="profileMenuOpen = false" class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-700 rounded-lg shadow-lg py-2">
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600 transition duration-300">
                                Editar Perfil
                            </a>
                            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="block px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600 transition duration-300">
                                Cerrar Sesión
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>
                <!-- Botón de Menú Móvil -->
                <div class="md:hidden flex items-center">
                    <button class="outline-none mobile-menu-button">
                        <svg class="w-6 h-6 text-gray-500 hover:text-blue-500" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                            <path d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        <!-- Menú Móvil -->
        <div class="hidden mobile-menu">
            <ul>
                <li><a href="{{ route('gastos.index') }}" class="block text-sm px-2 py-4 text-white bg-blue-500 font-semibold">Gastos</a></li>
                <li><a href="{{ route('ingresos.index') }}" class="block text-sm px-2 py-4 text-white bg-blue-500 font-semibold">Ingresos</a></li>
                <li><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="block text-sm px-2 py-4 text-white bg-red-500 font-semibold">Cerrar Sesión</a></li>
            </ul>
        </div>
    </nav>

    <!-- Contenido Principal -->
    <div class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <!-- Tarjeta de Acciones Rápidas -->
            <div class="col-span-1">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-4">Menú de Opciones</h3>
                    <a href="{{ route('gastos.create') }}" class="block w-full px-4 py-2 mb-4 bg-blue-600 text-white text-center font-semibold rounded-lg hover:bg-blue-700 transition duration-300 {{ $bloquearGastos ? 'opacity-50 cursor-not-allowed' : '' }}"
                        @if($bloquearGastos) onclick="event.preventDefault(); mostrarAlerta();" @endif>
                        Agregar Gasto
                    </a>
                    <a href="{{ route('ingresos.create') }}" class="block w-full px-4 py-2 mb-4 bg-green-600 text-white text-center font-semibold rounded-lg hover:bg-green-700 transition duration-300">
                        Agregar Ingreso
                    </a>
                    <a href="{{ route('reportes.index') }}" class="flex items-center justify-center w-full px-4 py-2 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition duration-300">
                        <i class="fas fa-file-alt mr-2"></i> Generar Reporte
                    </a>
                </div>
            </div>

            <!-- Tarjeta de Resumen -->
            <div class="col-span-3">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-6">Resumen</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div class="bg-blue-600 text-white rounded-lg p-6">
                            <h5 class="text-lg font-semibold">Gastos Totales</h5>
                            <p class="text-2xl font-bold">Bs {{ number_format($gastosTotales, 2) }}</p>
                        </div>
                        <div class="bg-green-600 text-white rounded-lg p-6">
                            <h5 class="text-lg font-semibold">Ingresos Totales</h5>
                            <p class="text-2xl font-bold">Bs {{ number_format($ingresosTotales, 2) }}</p>
                        </div>
                        <div class="bg-yellow-500 text-white rounded-lg p-6">
                            <h5 class="text-lg font-semibold">Saldo Restante</h5>
                            <p class="text-2xl font-bold">Bs {{ number_format($balance, 2) }}</p>
                        </div>
                        <div class="bg-indigo-600 text-white rounded-lg p-6">
                            <h5 class="text-lg font-semibold">Último Gasto</h5>
                            @if ($ultimoGasto)
                                <p class="text-2xl font-bold">Bs {{ number_format($ultimoGasto->monto, 2) }}</p>
                                <p class="text-sm">{{ $ultimoGasto->descripcion }}</p>
                            @else
                                <p class="text-2xl font-bold">No hay gastos registrados</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script para el Menú Móvil -->
    <script>
        const btn = document.querySelector(".mobile-menu-button");
        const menu = document.querySelector(".mobile-menu");

        btn.addEventListener("click", () => {
            menu.classList.toggle("hidden");
        });
    </script>

    <script>
        console.log({!! $gastosMensualesLabels !!});
        console.log({!! $gastosMensualesData !!});
         // Función para mostrar la alerta cuando el botón esté bloqueado
        function mostrarAlerta() {
            Swal.fire({
                icon: 'warning',
                title: '¡Atención!',
                text: 'No puedes agregar más gastos hasta que realices un nuevo ingreso.',
                confirmButtonText: 'Entendido',
                confirmButtonColor: '#3085d6',
            });
        }
    </script>

    <div class="mt-8">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-4">Gastos Mensuales</h2>
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg">
            <canvas id="gastosMensualesChart"></canvas>
        </div>
    </div>
    
    <!-- Script para el gráfico -->
    @push('scripts')
        
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const balance = {!! $balance !!}; // Obtener el balance desde el controlador
            let gastosMensualesChart; // Variable para almacenar la instancia del gráfico
    
            // Función para inicializar o actualizar el gráfico
            function inicializarGrafico() {
                const ctx = document.getElementById('gastosMensualesChart').getContext('2d');
                const darkMode = JSON.parse(localStorage.getItem('darkMode')) || false;
    
                // Colores para cada mes
                const coloresBarras = [
                    'rgba(255, 99, 132, 0.6)', // Enero
                    'rgba(54, 162, 235, 0.6)', // Febrero
                    'rgba(75, 192, 192, 0.6)', // Marzo
                    'rgba(153, 102, 255, 0.6)', // Abril
                    'rgba(255, 159, 64, 0.6)', // Mayo
                    'rgba(255, 205, 86, 0.6)', // Junio
                    'rgba(201, 203, 207, 0.6)', // Julio
                    'rgba(255, 99, 132, 0.6)', // Agosto
                    'rgba(54, 162, 235, 0.6)', // Septiembre
                    'rgba(75, 192, 192, 0.6)', // Octubre
                    'rgba(153, 102, 255, 0.6)', // Noviembre
                    'rgba(255, 159, 64, 0.6)'  // Diciembre
                ];
                if (balance <= 10) {
                Swal.fire({
                    icon: 'warning',
                    title: '¡Atención!',
                    text: 'Tu balance está por debajo de 10. No podrás realizar más gastos hasta que hagas un nuevo ingreso.',
                    confirmButtonText: 'Entendido',
                    confirmButtonColor: '#3085d6',
                });
            }
            
    
                // Destruye el gráfico anterior si existe
                if (gastosMensualesChart) {
                    gastosMensualesChart.destroy();
                }
    
                // Crea un nuevo gráfico
                gastosMensualesChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: {!! $gastosMensualesLabels !!}, // Etiquetas de los meses
                        datasets: [{
                            label: 'Gastos Mensuales', // Nombre del dataset
                            data: {!! $gastosMensualesData !!}, // Datos de gastos por mes
                            backgroundColor: coloresBarras.slice(0, {!! $gastosMensualesData !!}.length), // Colores para cada barra
                            borderColor: darkMode ? 'rgba(255, 255, 255, 1)' : 'rgba(59, 130, 246, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        animation: {
                            duration: 1000, // Duración de la animación
                            easing: 'easeInOutQuad' // Tipo de animación
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: darkMode ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)'
                                },
                                ticks: {
                                    color: darkMode ? '#fff' : '#1E3A8A' // Azul oscuro en modo claro
                                }
                            },
                            x: {
                                grid: {
                                    color: darkMode ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)'
                                },
                                ticks: {
                                    color: darkMode ? '#fff' : '#1E3A8A', // Azul oscuro en modo claro
                                    autoSkip: false,
                                    maxRotation: 0,
                                    callback: function(value, index, values) {
                                        return value; // Muestra la etiqueta completa
                                    }
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                labels: {
                                    color: darkMode ? '#fff' : '#1E3A8A' // Azul oscuro en modo claro
                                }
                            },
                            tooltip: {
                                enabled: true,
                                mode: 'index',
                                intersect: false,
                                backgroundColor: darkMode ? 'rgba(0, 0, 0, 0.8)' : 'rgba(255, 255, 255, 0.8)',
                                titleColor: darkMode ? '#fff' : '#1E3A8A', // Azul oscuro en modo claro
                                bodyColor: darkMode ? '#fff' : '#1E3A8A', // Azul oscuro en modo claro
                                borderColor: darkMode ? 'rgba(255, 255, 255, 0.2)' : 'rgba(0, 0, 0, 0.2)',
                                borderWidth: 1
                            }
                        }
                    }
                });
            }
    
            // Inicializa el gráfico por primera vez
            inicializarGrafico();
    
            // Escucha el cambio de modo oscuro/claro
        });
    </script>
    @endpush
        @stack('scripts')
</body>
</html>