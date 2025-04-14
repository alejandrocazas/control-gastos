<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Gasto;
use App\Models\Ingreso;
use App\Models\HistorialMensual;

class DashboardController extends Controller
{
    public function index()
    {
        $mesesEnEspanol = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
        ];

        $mesActual = Carbon::now()->format('Y-m');
        $ultimoMesRegistrado = HistorialMensual::latest()->first();

        // Verificar si ha cambiado el mes y no se ha procesado aún
        if ($this->debeProcesarMesAnterior($ultimoMesRegistrado, $mesActual)) {
            $this->procesarMesAnterior($ultimoMesRegistrado);
           // $this->reiniciarRegistros();
        }

        // Obtener los totales del mes actual
        $ingresosTotales = Ingreso::where('fecha', 'like', $mesActual . '%')->sum('monto');
        $gastosTotales = Gasto::where('fecha', 'like', $mesActual . '%')->sum('monto');
        $balance = $ingresosTotales - $gastosTotales;

        // Obtener datos para gráficos
        $datosGrafico = $this->obtenerDatosParaGrafico($mesesEnEspanol);

        // Verificar si se deben bloquear gastos
        $bloquearGastos = $balance <= 10;

        return view('dashboard', [
            'gastosTotales' => $gastosTotales,
            'ingresosTotales' => $ingresosTotales,
            'balance' => $balance,
            'ultimoGasto' => Gasto::latest()->first(),
            'gastosMensualesLabels' => json_encode($datosGrafico['labels']),
            'gastosMensualesData' => json_encode($datosGrafico['data']),
            'bloquearGastos' => $bloquearGastos,
        ]);
    }

    /**
     * Determina si se debe procesar el mes anterior
     */
    private function debeProcesarMesAnterior($ultimoMesRegistrado, $mesActual)
    {
        return $ultimoMesRegistrado && 
            $ultimoMesRegistrado->mes !== $mesActual &&
            !HistorialMensual::where('mes', $ultimoMesRegistrado->mes)->exists();
    }

    /**
     * Procesa y guarda el historial del mes anterior
     */
    private function procesarMesAnterior($ultimoMesRegistrado)
    {
        HistorialMensual::create([
            'mes' => $ultimoMesRegistrado->mes,
            'ingresos_totales' => Ingreso::where('fecha', 'like', $ultimoMesRegistrado->mes . '%')->sum('monto'),
            'gastos_totales' => Gasto::where('fecha', 'like', $ultimoMesRegistrado->mes . '%')->sum('monto'),
            'balance' => Ingreso::where('fecha', 'like', $ultimoMesRegistrado->mes . '%')->sum('monto') - 
                        Gasto::where('fecha', 'like', $ultimoMesRegistrado->mes . '%')->sum('monto'),
        ]);
    }

    /**
     * Reinicia los registros para el nuevo mes
     */
    

    /**
     * Obtiene datos para gráficos mensuales
     */
    private function obtenerDatosParaGrafico($mesesEnEspanol)
    {
        $gastosMensuales = Gasto::selectRaw('YEAR(fecha) as year, MONTH(fecha) as month, SUM(monto) as total')
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        $labels = [];
        $data = [];

        foreach ($gastosMensuales as $gasto) {
            if (is_numeric($gasto->year) && is_numeric($gasto->month)) {
                $labels[] = $mesesEnEspanol[$gasto->month];
                $data[] = $gasto->total;
            }
        }

        return ['labels' => $labels, 'data' => $data];
    }
}