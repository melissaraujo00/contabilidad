{{-- resources/views/partidas/reporte.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Partidas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
        }

        .header h2 {
            margin: 0;
            font-size: 20px;
            color: #1a73e8; /* Azul primario */
        }

        .subheader {
            text-align: center;
            margin-bottom: 20px;
            font-size: 12px;
            color: #555;
        }

        /* --- Estilo de la Tabla --- */

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            /* Contenedor para simular el account-section si se desea */
            border: 1px solid #ddd;
            border-radius: 4px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        th, td {
            padding: 8px 15px; /* Ajustado el padding para más espacio */
            text-align: left;
            border: none;
            border-bottom: 1px solid #eee; /* Línea de separación sutil */
        }

        thead tr:first-child th {
            background-color: #eef4fb; /* Fondo azul claro del Libro Mayor */
            color: #1a73e8; /* Color de encabezado azul primario */
            font-weight: bold;
            border-bottom: 2px solid #d4e3f1; /* Línea inferior más fuerte */
        }
        
        tbody tr:last-child td {
            border-bottom: none; /* Eliminar borde inferior de la última fila del cuerpo */
        }

        .right {
            text-align: right;
        }

        /* --- Estilos de Estado (Partidas) --- */
        .estado-activa {
            color: #007000; /* Verde */
            font-weight: bold;
            background-color: #e6ffe6; /* Fondo muy claro para destacar */
            padding: 2px 6px;
            border-radius: 3px;
        }

        .estado-inactiva {
            color: #c50000; /* Rojo */
            font-weight: bold;
            background-color: #ffeded; /* Fondo muy claro para destacar */
            padding: 2px 6px;
            border-radius: 3px;
        }
        
        /* Estilo para Totales al final del reporte (opcional si se agregan totales) */
        .report-totals-row td {
            background: #dce6f1;
            font-weight: bold;
            color: #1a73e8;
            border-top: 2px solid #a6c2e3;
            font-size: 12px;
        }

    </style>
</head>
<body>

    <div class="header">
        <h2>REPORTE DE PARTIDAS</h2>
    </div>

    <div class="subheader">
        <p><strong>Desde:</strong> {{ $fechaInicio ?? 'Inicio' }} — <strong>Hasta:</strong> {{ $fechaFin ?? 'Fin' }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Partida #</th>
                <th>Fecha Partida</th>
                <th>Tipo</th>
                <th class="right">Total Debe</th>
                <th class="right">Total Haber</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalDebeGeneral = 0;
                $totalHaberGeneral = 0;
            @endphp
            @foreach ($partidas as $partida)
                <tr>
                    <td>{{ $partida->id }}</td>
                    <td>{{ $partida->partida_numero }}</td>
                    <td>{{ $partida->fecha_partida }}</td>
                    <td>{{ $partida->tipo_partida }}</td>
                    <td class="right">${{ number_format($partida->total_debe, 2) }}</td>
                    <td class="right">${{ number_format($partida->total_haber, 2) }}</td>
                    <td>
                        <span class="{{ $partida->estado ? 'estado-activa' : 'estado-inactiva' }}">
                            {{ $partida->estado ? 'Activa' : 'Inactiva' }}
                        </span>
                    </td>
                </tr>
                @php
                    // Acumular totales para el resumen final
                    $totalDebeGeneral += $partida->total_debe;
                    $totalHaberGeneral += $partida->total_haber;
                @endphp
            @endforeach
        </tbody>
        <tfoot>
            {{-- Agregando una fila de totales para mantener la coherencia con reportes contables --}}
            <tr class="report-totals-row">
                <td colspan="4" class="right">TOTAL GENERAL:</td>
                <td class="right">${{ number_format($totalDebeGeneral, 2) }}</td>
                <td class="right">${{ number_format($totalHaberGeneral, 2) }}</td>
                <td></td>
            </tr>
        </tfoot>
    </table>

</body>
</html>