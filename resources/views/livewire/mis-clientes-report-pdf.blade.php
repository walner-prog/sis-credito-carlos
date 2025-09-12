<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Abonos</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.4;
        }
        h1, h2 {
            text-align: center;
            margin-bottom: 10px;
        }
        .resumen {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }
        .resumen td {
            padding: 8px;
            border: 1px solid #ccc;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 6px 8px;
            text-align: left;
        }
        th {
            background-color: #f0f0f0;
        }
        .text-right {
            text-align: right;
        }
        .estado-abono {
            padding: 2px 5px;
            color: #fff;
            border-radius: 4px;
            font-size: 11px;
            text-align: center;
        }
        .abonado { background-color: #38a169; } /* verde */
        .no-abonado { background-color: #e53e3e; } /* rojo */
        .activo { background-color: #3182ce; } /* azul */
        .moroso { background-color: #c53030; } /* rojo oscuro */
    </style>
</head>
<body>
    <h1>Reporte de Abonos del Día</h1>
    <h2>{{ now()->format('d/m/Y H:i') }}</h2>

    <!-- Resumen -->
    <table class="resumen">
        <tr>
            <td><strong>Total Abonado:</strong> C$ {{ number_format($totalAbonado, 2) }}</td>
            <td><strong>Clientes que abonaron:</strong> {{ $clientesAbonaron }}</td>
            <td><strong>Clientes que NO abonaron:</strong> {{ $clientesNoAbonaron }}</td>
        </tr>
    </table>

    <!-- Tabla de clientes -->
    <table>
        <thead>
            <tr>
                <th>Cliente</th>
                <th>Monto Crédito (C$)</th>
                <th>Monto Abonado (C$)</th>
                <th>Estado Abono</th>
                <th>Estado Crédito</th>
                <th>Fecha Abono</th>
                <th>Comentarios</th>
            </tr>
        </thead>
        <tbody>
            @foreach($clientes as $cliente)
                @foreach($cliente->detalle_abonos as $abono)
                <tr>
                    <td>{{ $cliente->nombres }} {{ $cliente->apellidos }}</td>
                    <td class="text-right">C$ {{ number_format($abono->monto_credito, 2) }}</td>
                    <td class="text-right">C$ {{ number_format($abono->monto_abono, 2) }}</td>
                    <td>
                        <span class="estado-abono {{ $abono->estado === 'Pagó Hoy' ? 'abonado' : 'no-abonado' }}">
                            {{ $abono->estado }}
                        </span>
                    </td>
                    <td>
                        <span class="estado-abono {{ $abono->estado_credito === 'activo' ? 'activo' : 'moroso' }}">
                            {{ ucfirst($abono->estado_credito) }}
                        </span>
                    </td>
                    <td>{{ $abono->fecha_abono ? \Carbon\Carbon::parse($abono->fecha_abono)->format('d/m/Y') : '-' }}</td>
                    <td>{{ $abono->comentarios ?? '-' }}</td>
                </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>

    <p style="text-align: center; font-size: 11px; margin-top: 20px;">
        Generado por el sistema - {{ now()->format('d/m/Y H:i') }}
    </p>
</body>
</html>
