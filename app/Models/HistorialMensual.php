<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistorialMensual extends Model
{
    use HasFactory;

    // Nombre de la tabla (opcional si sigue la convención de nombres)
    protected $table = 'historial_mensual';

    // Campos asignables masivamente
    protected $fillable = [
        'mes',
        'ingresos_totales',
        'gastos_totales',
        'balance',
    ];

    // Campos ocultos (opcional)
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}