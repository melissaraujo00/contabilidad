<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte del Libro Mayor</title>
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
            color: #1a73e8; 
        }

        .subheader {
            text-align: center;
            margin-bottom: 20px;
            font-size: 12px;
            color: #555;
        }

        /* --- Estilo de la Sección de Cuenta --- */

        .account-section {
            margin-bottom: 30px;
            border: 1px solid #ddd; 
            border-radius: 4px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05); 
        }

        .cuenta-title-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 15px;
            background: #eef4fb; 
            border-bottom: 1px solid #d4e3f1;
            font-size: 14px;
            color: #1a73e8; 
            font-weight: bold;
            border-top-left-radius: 4px;
            border-top-right-radius: 4px;
        }
        
        .saldo-inicial {
            font-size: 13px;
            font-weight: normal;
            color: #007000; 
        }

        /* --- Estilo de la Tabla --- */

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
        }

        th, td {
            padding: 8px 15px;
            text-align: left;
            border: none;
            border-bottom: 1px solid #eee; 
        }

        thead tr:first-child th {
            background-color: #f8f8f8;
            color: #555;
            font-weight: normal;
            border-bottom: 2px solid #ddd;
        }

        .right {
            text-align: right;
        }

        /* --- Estilo de Totales y Saldo Final --- */

        .totals td {
            background: #f2f2f2;
            font-weight: bold;
            border-bottom: 1px solid #ccc;
        }
        
        .saldo-final-row td {
            background: #dce6f1; 
            font-weight: bold;
            color: #1a73e8; 
            border-bottom: none;
        }
        
        .saldo-final-valor {
            font-size: 14px;
            padding: 10px 15px;
        }

        .deudor {
            color: #007000; 
        }

        .acreedor {
            color: #c50000; 
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
        <div class="account-section">
            
            @php
                // Cálculo del Saldo de la cuenta (Debe - Haber del periodo)
                $saldo = $cuenta['total_debe'] - $cuenta['total_haber'];
            @endphp
            
            <div class="cuenta-title-row">
                <span>{{ $cuenta['codigo'] }} - {{ $cuenta['nombre'] }}</span>
                <span class="saldo-inicial">
                    Saldo: 
                    <span class="{{ $saldo >= 0 ? 'deudor' : 'acreedor' }}">
                        $ {{ number_format(abs($saldo), 2) }}
                    </span>
                </span>
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
                        <td colspan="3" class="right">Sumas del Periodo:</td>
                        <td class="right">{{ number_format($cuenta['total_debe'], 2) }}</td>
                        <td class="right">{{ number_format($cuenta['total_haber'], 2) }}</td>
                    </tr>

                    <tr class="saldo-final-row">
                        <td colspan="3" class="right">Saldo Actual:</td>
                        <td colspan="2" class="right saldo-final-valor">
                            ${{ number_format(abs($saldo), 2) }}
                            (<span class="{{ $saldo >= 0 ? 'deudor' : 'acreedor' }}">
                                {{ $saldo >= 0 ? 'Deudor' : 'Acreedor' }}
                            </span>)
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    @endforeach

</body>
</html>