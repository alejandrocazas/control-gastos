<div class="space-y-6">
    <div>
        <label for="descripcion" class="block text-sm font-medium text-gray-700">Descripción</label>
        <input type="text" id="descripcion" name="descripcion" value="{{ old('descripcion', $ingreso->descripcion ?? '') }}" required
            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
    </div>
    <div>
        <label for="monto" class="block text-sm font-medium text-gray-700">Monto</label>
        <input type="number" step="0.01" id="monto" name="monto" value="{{ old('monto', $ingreso->monto ?? '') }}" required
            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
    </div>
    <div>
        <label for="fecha" class="block text-sm font-medium text-gray-700">Fecha</label>
        <input type="date" id="fecha" name="fecha" value="{{ old('fecha', $ingreso->fecha ?? '') }}" required
            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
    </div>
    <div class="flex space-x-4">
        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <i class="fas fa-save mr-2"></i> {{ isset($ingreso) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('ingreso.index') }}" class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <i class="fas fa-arrow-left mr-2"></i> Cancelar
        </a>
    </div>
</div>