<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Créditos</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        h2 { margin-bottom: 5px; }
        p { margin: 2px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #333; padding: 5px; text-align: center; }
        th { background-color: #eee; }
    </style>
</head>
<body>
    <h2>Reporte de Créditos</h2>
    <p>Generado: {{ $fecha }}</p>
    <p>Total Créditos: {{ $creditos->count() }}</p>

    <table>
        <thead>
            <tr>
                <th>Cliente</th>
                <th>Monto Solicitado</th>
                <th>Monto Total</th>
                <th>Saldo Pendiente</th>
                <th>Plazo</th>
                <th>Tasa %</th>
                <th>Cuota</th>
                <th>Frecuencia</th>
                <th>Nº Cuotas</th>
                <th>Creado</th>
                <th>Vencimiento</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($creditos as $credito)
                <tr>
                    <td>{{ $credito->cliente?->nombres }} {{ $credito->cliente?->apellidos }}</td>
                    <td>C$ {{ number_format($credito->monto_solicitado, 2) }}</td>
                    <td>C$ {{ number_format($credito->monto_total, 2) }}</td>
                    <td>C$ {{ number_format($credito->saldo_pendiente, 2) }}</td>
                    <td>{{ $credito->plazo }} {{ $credito->unidad_plazo }}</td>
                    <td>{{ $credito->tasa_interes }}%</td>
                    <td>C$ {{ number_format($credito->cuota, 2) }}</td>
                    <td>{{ ucfirst($credito->cuota_frecuencia) }}</td>
                    <td>{{ $credito->num_cuotas }}</td>
                    <td>{{ $credito->fecha_inicio }}</td>
                    <td>{{ $credito->fecha_vencimiento }}</td>
                    <td>{{ ucfirst($credito->estado) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
