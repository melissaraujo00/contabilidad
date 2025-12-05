<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Balance General</title>
    {{-- Usar DejaVu Sans si se requiere para la generación de PDF con caracteres especiales (Laravel-Dompdf) --}}
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 5px;
        }

        .header h2 {
            margin: 0;
            font-size: 20px;
            color: #1a73e8; 
            border-bottom: 2px solid #1a73e8;
            display: inline-block;
            padding-bottom: 3px;
        }

        .subheader {
            text-align: center;
            margin-bottom: 20px;
            font-size: 12px;
            color: #555;
            font-weight: bold;
        }

        h3 {
            font-size: 14px;
            color: #000;
            border-bottom: 1px solid #ccc;
            padding-bottom: 3px;
            margin-top: 25px;
            margin-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            padding: 6px 15px;
            text-align: left;
            border: none;
            border-bottom: 1px solid #eee; 
        }

        thead tr:first-child th {
            background-color: #eef4fb; 
            color: #1a73e8; 
            font-weight: bold;
            border-bottom: 2px solid #d4e3f1;
        }
        
        tbody tr:last-child td {
             border-bottom: none; 
        }

        .right {
            text-align: right;
        }


        .total-section-row th {
            background: #dce6f1; 
            font-weight: bold;
            color: #1a73e8;
            border-top: 1px solid #a6c2e3;
            border-bottom: 2px solid #a6c2e3;
            font-size: 12px;
        }

        .resultado-ejercicio-row td {
            font-weight: bold;
            color: #555;
            border-top: 1px dashed #ccc;
        }

    </style>
</head>
<body>

    <div class="header">
        <h2>BALANCE GENERAL</h2>
    </div>
    
    <div class="subheader">
        <p>Al {{ $fechaCorte }}</p>
    </div>

    {{-- --- ACTIVO --- --}}
    <h3>Activos</h3>
    <table>
        <thead>
            <tr>
                <th style="width: 15%;">Código</th>
                <th>Cuenta</th>
                <th class="right" style="width: 25%;">Saldo</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($activos as $a)
            <tr>
                <td>{{ $a->codigo }}</td>
                <td>{{ $a->cuenta }}</td>
                <td class="right">${{ number_format($a->saldo_actual, 2) }}</td>
            </tr>
            @endforeach
            <tr class="total-section-row">
                <th colspan="2">Total Activos</th>
                <th class="right">${{ number_format($totales['totalActivo'], 2) }}</th>
            </tr>
        </tbody>
    </table>

    {{-- --- PASIVO --- --}}
    <h3>Pasivos</h3>
    <table>
        <thead>
            <tr>
                <th style="width: 15%;">Código</th>
                <th>Cuenta</th>
                <th class="right" style="width: 25%;">Saldo</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pasivos as $p)
            <tr>
                <td>{{ $p->codigo }}</td>
                <td>{{ $p->cuenta }}</td>
                <td class="right">${{ number_format($p->saldo_actual, 2) }}</td>
            </tr>
            @endforeach
            <tr class="total-section-row">
                <th colspan="2">Total Pasivos</th>
                <th class="right">${{ number_format($totales['totalPasivo'], 2) }}</th>
            </tr>
        </tbody>
    </table>

    {{-- --- PATRIMONIO --- --}}
    <h3>Patrimonio</h3>
    <table>
        <thead>
            <tr>
                <th style="width: 15%;">Código</th>
                <th>Cuenta</th>
                <th class="right" style="width: 25%;">Saldo</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($patrimonio as $pa)
            <tr>
                <td>{{ $pa->codigo }}</td>
                <td>{{ $pa->cuenta }}</td>
                <td class="right">${{ number_format($pa->saldo_actual, 2) }}</td>
            </tr>
            @endforeach

            <tr class="resultado-ejercicio-row">
                <td></td>
                <td><strong>Resultado del Ejercicio</strong></td>
                <td class="right"><strong>${{ number_format($resultadoEjercicio, 2) }}</strong></td>
            </tr>

            <tr class="total-section-row">
                <th colspan="2">Total Patrimonio</th>
                <th class="right">${{ number_format($totales['totalPatrimonio'], 2) }}</th>
            </tr>
        </tbody>
    </table>

</body>
</html>