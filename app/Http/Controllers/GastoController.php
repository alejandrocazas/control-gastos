<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gasto;

class GastoController extends Controller
{
    
    public function index()
    {
        
        // Obtener los ingresos ordenados por fecha (el más reciente primero)
        $gastos = Gasto::orderBy('fecha', 'desc')->paginate(10); // Paginar de 10 en 10

    // Pasar los ingresos a la vista
    return view('gastos.index', [
        'gastos' => $gastos,
    ]);
    }

    // Mostrar formulario para crear un gasto
    public function create()
    {
        return view('gastos.create');
    }

    // Guardar un nuevo gasto
    public function store(Request $request)
    {
        $request->validate([
            'descripcion' => 'required|string|max:255',
            'monto' => 'required|numeric|min:0',
            'fecha' => 'required|date',
        ]);

        Gasto::create($request->all());

        return redirect()->route('gastos.index')->with('success', 'Gasto agregado correctamente.');
    }

    // Mostrar formulario para editar un gasto
    public function edit(Gasto $gasto)
    {
        return view('gastos.edit', compact('gasto'));
    }

    // Actualizar un gasto
    public function update(Request $request, Gasto $gasto)
{
    $request->validate([
        'descripcion' => 'required|string|max:255',
        'monto' => 'required|numeric|min:0',
        'fecha' => 'required|date',
    ]);

    $gasto->update($request->all());

    // Para API/Axios: return response()->json(['success' => 'Gasto actualizado correctamente.']);
    return redirect()->route('gastos.index')
        ->with('swal', [
            'icon' => 'success',
            'title' => '¡Éxito!',
            'text' => 'Gasto actualizado correctamente.',
        ]);
}

    // Eliminar un gasto
    public function destroy(Gasto $gasto)
{
    $gasto->delete();
    
    return redirect()->route('gastos.index')
        ->with('success', 'Gasto eliminado correctamente');
}
}
