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
        <div class="flex items-center space-x-4">
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
    </div>
</nav>