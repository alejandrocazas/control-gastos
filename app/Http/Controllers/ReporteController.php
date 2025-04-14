<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gasto;
use App\Models\Ingreso;
use App\Models\Reporte;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ReporteController extends Controller
{
    public function index()
    {
        return view('reportes.index');
    }

    public function generar(Request $request)
    {
        $validated = $this->validarRequest($request);
        
        $data = $this->generarDatosReporte(
            $validated['tipo_reporte'],
            $validated['tipo_operacion'],
            $validated['fecha_inicio'],
            $validated['fecha_fin']
        );

        $this->guardarReporte($data, $validated['tipo_reporte']);

        return view('reportes.resultado', compact('data'));
    }

    public function exportarPDF(Request $request)
    {
        $validated = $request->validate([
            'tipo_reporte' => 'required|in:dias,semanas,meses',
            'tipo_operacion' => 'required|in:gastos,ingresos,ambos',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio'
        ]);
    
        $data = $this->generarDatosReporte(
            $validated['tipo_reporte'],
            $validated['tipo_operacion'],
            $validated['fecha_inicio'],
            $validated['fecha_fin']
        );
    
        return Pdf::loadView('reportes.pdf', compact('data'))
            ->setPaper('a4', 'landscape')
            ->download('reporte-'.now()->format('Y-m-d').'.pdf');
    }

    // Métodos privados
    private function validarRequest(Request $request)
    {
        return $request->validate([
            'tipo_reporte' => 'required|in:dias,semanas,meses',
            'tipo_operacion' => 'required|in:gastos,ingresos,ambos',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio'
        ]);
    }

    private function guardarReporte(array $data, string $tipoReporte)
    {
        Reporte::create([
            'mes' => Carbon::now()->format('Y-m'),
            'total_ingresos' => $data['total_ingresos'] ?? 0,
            'total_gastos' => $data['total_gastos'] ?? 0,
            'tipo_reporte' => $tipoReporte
        ]);
    }

    private function generarDatosReporte($tipoReporte, $tipoOperacion, $fechaInicio, $fechaFin)
    {
        $fechaInicio = Carbon::parse($fechaInicio);
        $fechaFin = Carbon::parse($fechaFin);

        switch ($tipoReporte) {
            case 'dias':
                return $this->reporteDiario($tipoOperacion, $fechaInicio, $fechaFin);
            case 'semanas':
                return $this->reporteSemanal($tipoOperacion, $fechaInicio, $fechaFin);
            case 'meses':
                return $this->reporteMensual($tipoOperacion, $fechaInicio, $fechaFin);
            default:
                throw new \InvalidArgumentException("Tipo de reporte no válido");
        }
    }

    private function reporteDiario($tipoOperacion, $fechaInicio, $fechaFin)
{
    $resultados = ['detalle' => [], 'gastos_detallados' => []];
    $totalIngresos = 0;
    $totalGastos = 0;

    for ($fecha = $fechaInicio; $fecha <= $fechaFin; $fecha->addDay()) {
        $dia = $fecha->format('Y-m-d');
        $ingresos = 0;
        $gastos = 0;
        $gastosDelDia = [];

        if (in_array($tipoOperacion, ['ingresos', 'ambos'])) {
            $ingresos = (float) Ingreso::whereDate('fecha', $dia)->sum('monto');
            $totalIngresos += $ingresos;
        }
        
        if (in_array($tipoOperacion, ['gastos', 'ambos'])) {
            $gastos = (float) Gasto::whereDate('fecha', $dia)->sum('monto');
            $totalGastos += $gastos;
            
            $detallesGastos = Gasto::whereDate('fecha', $dia)
                ->select('descripcion', 'monto', 'fecha')
                ->get()
                ->map(function($gasto) {
                    return [
                        'descripcion' => $gasto->descripcion,
                        'monto' => (float) $gasto->monto,
                        'fecha' => Carbon::parse($gasto->fecha)->format('Y-m-d') // Convertir a Carbon primero
                    ];
                })->toArray();
            
            $gastosDelDia = $detallesGastos;
        }

        $resultados['detalle'][] = [
            'fecha' => $dia,
            'ingresos' => $ingresos,
            'gastos' => $gastos
        ];

        if (!empty($gastosDelDia)) {
            $resultados['gastos_detallados'][$dia] = $gastosDelDia;
        }
    }

    $resultados['total_ingresos'] = $totalIngresos;
    $resultados['total_gastos'] = $totalGastos;
    $resultados['tipo_reporte'] = 'Diario';

    return $resultados;
}

private function reporteSemanal($tipoOperacion, $fechaInicio, $fechaFin)
{
    $resultados = ['detalle' => [], 'gastos_detallados' => []];
    $totalIngresos = 0;
    $totalGastos = 0;
    $fechaActual = $fechaInicio->copy()->startOfWeek();
    
    while ($fechaActual <= $fechaFin) {
        $inicioSemana = $fechaActual->copy()->format('Y-m-d');
        $finSemana = $fechaActual->copy()->endOfWeek()->format('Y-m-d');
        
        $ingresos = 0;
        $gastos = 0;
        $gastosDeLaSemana = [];

        if (in_array($tipoOperacion, ['ingresos', 'ambos'])) {
            $ingresos = (float) Ingreso::whereBetween('fecha', [
                $fechaActual->copy()->startOfWeek(),
                $fechaActual->copy()->endOfWeek()
            ])->sum('monto');
            $totalIngresos += $ingresos;
        }
        
        if (in_array($tipoOperacion, ['gastos', 'ambos'])) {
            $gastos = (float) Gasto::whereBetween('fecha', [
                $fechaActual->copy()->startOfWeek(),
                $fechaActual->copy()->endOfWeek()
            ])->sum('monto');
            
            $totalGastos += $gastos;
            
            $gastosDeLaSemana = Gasto::whereBetween('fecha', [
                $fechaActual->copy()->startOfWeek(),
                $fechaActual->copy()->endOfWeek()
            ])
            ->select('descripcion', 'monto', 'fecha')
            ->get()
            ->map(function($gasto) {
                return [
                    'descripcion' => $gasto->descripcion,
                    'monto' => (float) $gasto->monto,
                    'fecha' => Carbon::parse($gasto->fecha)->format('Y-m-d')
                ];
            })->toArray();
        }

        $resultados['detalle'][] = [
            'periodo' => "Semana del {$inicioSemana} al {$finSemana}",
            'ingresos' => $ingresos,
            'gastos' => $gastos
        ];

        if (!empty($gastosDeLaSemana)) {
            $resultados['gastos_detallados']["Semana del {$inicioSemana} al {$finSemana}"] = $gastosDeLaSemana;
        }

        $fechaActual->addWeek();
    }

    $resultados['total_ingresos'] = $totalIngresos;
    $resultados['total_gastos'] = $totalGastos;
    $resultados['tipo_reporte'] = 'Semanal';

    return $resultados;
}

    private function reporteMensual($tipoOperacion, $fechaInicio, $fechaFin)
    {
        $resultados = ['detalle' => [], 'gastos_detallados' => []];
        $totalIngresos = 0;
        $totalGastos = 0;
        $fechaActual = $fechaInicio->copy()->startOfMonth();
        
        try {
            while ($fechaActual <= $fechaFin) {
                $mes = $fechaActual->format('F Y');
                $inicioMes = $fechaActual->copy()->startOfMonth()->format('Y-m-d');
                $finMes = $fechaActual->copy()->endOfMonth()->format('Y-m-d');
                
                $ingresos = 0;
                $gastos = 0;
                $gastosDelMes = [];
    
                if (in_array($tipoOperacion, ['ingresos', 'ambos'])) {
                    $ingresos = (float) Ingreso::whereBetween('fecha', [
                        $fechaActual->copy()->startOfMonth(),
                        $fechaActual->copy()->endOfMonth()
                    ])->sum('monto');
                    $totalIngresos += $ingresos;
                }
                
                if (in_array($tipoOperacion, ['gastos', 'ambos'])) {
                    $gastos = (float) Gasto::whereBetween('fecha', [
                        $fechaActual->copy()->startOfMonth(),
                        $fechaActual->copy()->endOfMonth()
                    ])->sum('monto');
                    
                    $totalGastos += $gastos;
                    
                    $gastosDelMes = Gasto::whereBetween('fecha', [
                        $fechaActual->copy()->startOfMonth(),
                        $fechaActual->copy()->endOfMonth()
                    ])
                    ->select('descripcion', 'monto', 'fecha')
                    ->get()
                    ->map(function($gasto) {
                        return [
                            'descripcion' => $gasto->descripcion,
                            'monto' => (float) $gasto->monto,
                            'fecha' => Carbon::parse($gasto->fecha)->format('Y-m-d')
                        ];
                    })->toArray();
                }
    
                $resultados['detalle'][] = [
                    'periodo' => $mes,
                    'ingresos' => $ingresos,
                    'gastos' => $gastos
                ];
    
                if (!empty($gastosDelMes)) {
                    $resultados['gastos_detallados'][$mes] = $gastosDelMes;
                }
    
                $fechaActual->addMonth();
            }
        } catch (\Exception $e) {
            throw new \RuntimeException("Error al generar reporte mensual: " . $e->getMessage());
        }
    
        $resultados['total_ingresos'] = $totalIngresos;
        $resultados['total_gastos'] = $totalGastos;
        $resultados['tipo_reporte'] = 'Mensual';
    
        return $resultados;
    }
}
