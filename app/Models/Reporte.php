<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reporte extends Model
{
    use HasFactory;

    protected $fillable = [
        'mes',
        'total_ingresos',
        'total_gastos',
        'tipo_reporte'
    ];

    protected $casts = [
        'total_ingresos' => 'decimal:2',
        'total_gastos' => 'decimal:2'
    ];
}
