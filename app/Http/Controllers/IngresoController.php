<?php

namespace App\Http\Controllers;

use App\Models\Ingreso;
use Illuminate\Http\Request;

class IngresoController extends Controller
{
      // Mostrar lista de gastos
    public function index()
    {
         // Obtener los ingresos ordenados por fecha (el más reciente primero)
    $ingresos = Ingreso::orderBy('fecha', 'desc')->paginate(10); // Paginar de 10 en 10

    // Pasar los ingresos a la vista
    return view('ingresos.index', [
        'ingresos' => $ingresos,
    ]);
    }
      // Mostrar formulario para crear un gasto
    public function create()
    {
        return view('ingresos.create');
    }
      // Guardar un nuevo gasto
    public function store(Request $request)
    {
        $request->validate([
            'descripcion' => 'required|string|max:255',
            'monto' => 'required|numeric|min:0',
            'fecha' => 'required|date',
        ]);
        Ingreso::create($request->all());
        return redirect()->route('ingresos.index')->with('success', 'Ingreso agregado correctamente.');
    }
      // Mostrar formulario para editar un gasto
    public function edit(Ingreso $ingreso)
    {
        return view('ingresos.edit', compact('ingreso'));
    }
      // Actualizar un gasto
    public function update(Request $request, Ingreso $ingreso)
    {
        $request->validate([
            'descripcion' => 'required|string|max:255',
            'monto' => 'required|numeric|min:0',
            'fecha' => 'required|date',
        ]);
        $ingreso->update($request->all());

        // Para API/Axios: return response()->json(['success' => 'Gasto actualizado correctamente.']);
    return redirect()->route('ingresos.index')
        ->with('swal', [
            'icon' => 'success',
            'title' => '¡Éxito!',
            'text' => 'Gasto actualizado correctamente.',
        ]);
    }
      // Eliminar un gasto
    public function destroy(Ingreso $ingreso)
    {
        $ingreso->delete();
        return redirect()->route('ingresos.index')
        ->with('success', 'Ingreso eliminado correctamente');
    }
}
