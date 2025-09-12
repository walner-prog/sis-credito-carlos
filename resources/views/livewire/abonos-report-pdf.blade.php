<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Abonos</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #333; padding: 5px; text-align: center; }
        th { background-color: #eee; }
    </style>
</head>
<body>
    <h2>Reporte de Abonos - {{ now()->format('d/m/Y') }}</h2>
    <p>Total Abonado: C$ {{ number_format($totalAbonado, 2) }}</p>
    <p>Clientes que Abonaron: {{ $clientesAbonaron }}</p>
    <p>Clientes que No Abonaron: {{ $clientesNoAbonaron }}</p>

    <table>
        <thead>
            <tr>
                <th>Registrado por</th>
                <th>Cliente</th>
                <th>Monto Crédito</th>
                <th>Monto Abono</th>
                <th>Estado Abono</th>
                <th>Estado Crédito</th>
                <th>Fecha</th>
                <th>Comentarios</th>
            </tr>
        </thead>
        <tbody>
            @foreach($clientes as $cliente)
                @foreach($cliente->detalle_abonos as $abono)
                    <tr>
                        <td>{{ $abono->user ?? '-' }}</td>
                        <td>{{ $abono->cliente }}</td>
                        <td>C$ {{ number_format($abono->monto_credito, 2) }}</td>
                        <td>C$ {{ number_format($abono->monto_abono, 2) }}</td>
                        <td>{{ $abono->estado }}</td>
                        <td>{{ ucfirst($abono->estado_credito) }}</td>
                        <td>{{ $abono->fecha_abono ? \Carbon\Carbon::parse($abono->fecha_abono)->format('d/m/Y') : '-' }}</td>
                        <td>{{ $abono->comentarios ?? '-' }}</td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
</body>
</html>
