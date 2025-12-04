<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte del Libro Mayor</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h2 {
            margin: 0;
            font-size: 20px;
        }

        .subheader {
            text-align: center;
            margin-bottom: 10px;
            font-size: 13px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }

        th, td {
            border: 1px solid #444;
            padding: 6px;
            text-align: left;
        }

        th {
            background: #eee;
        }

        .right {
            text-align: right;
        }

        .totals {
            background: #f2f2f2;
            font-weight: bold;
        }

        .cuenta-title {
            font-weight: bold;
            font-size: 14px;
            background: #dce6f1;
            padding: 6px;
            margin-top: 20px;
            margin-bottom: 5px;
            border: 1px solid #444;
        }

        .saldo {
            font-weight: bold;
            text-align: right;
            margin-bottom: 10px;
        }

    </style>
</head>
<body>

    <div class="header">
        <h2>LIBRO MAYOR</h2>
    </div>

    <div class="subheader">
        <p><strong>Desde:</strong> {{ $fechaInicio }} — <strong>Hasta:</strong> {{ $fechaFin }}</p>
    </div>

    @foreach ($cuentas as $cuenta)
        <div class="cuenta-title">
            {{ $cuenta['codigo'] }} - {{ $cuenta['nombre'] }}
        </div>

        <table>
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Partida #</th>
                    <th>Concepto</th>
                    <th class="right">Debe</th>
                    <th class="right">Haber</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($cuenta['movimientos'] as $mov)
                    <tr>
                        <td>{{ $mov['fecha'] }}</td>
                        <td>#{{ $mov['partida_numero'] }}</td>
                        <td>{{ $mov['concepto'] }}</td>
                        <td class="right">
                            {{ $mov['debe'] > 0 ? number_format($mov['debe'], 2) : '-' }}
                        </td>
                        <td class="right">
                            {{ $mov['haber'] > 0 ? number_format($mov['haber'], 2) : '-' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>

            <tfoot>
                <tr class="totals">
                    <td colspan="3" class="right">Sumas del periodo:</td>
                    <td class="right">{{ number_format($cuenta['total_debe'], 2) }}</td>
                    <td class="right">{{ number_format($cuenta['total_haber'], 2) }}</td>
                </tr>

                <tr class="totals">
                    <td colspan="3" class="right">Saldo Final:</td>

                    @php
                        $saldo = $cuenta['total_debe'] - $cuenta['total_haber'];
                    @endphp

                    <td colspan="2" class="right">
                        {{ number_format(abs($saldo), 2) }}
                        ({{ $saldo >= 0 ? 'Deudor' : 'Acreedor' }})
                    </td>
                </tr>
            </tfoot>
        </table>
    @endforeach

</body>
</html>
