<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Nuevo pago recibido - Sofnica</title>
</head>
<body>
    <h2>¡Nuevo pago recibido!</h2>

    <p><strong>Order ID:</strong> {{ $pago->paypal_order_id }}</p>
    <p><strong>Email del comprador:</strong> {{ $pago->payer_email }}</p>
    <p><strong>Descripción:</strong> {{ $pago->description ?? 'No disponible' }}</p>
    <p><strong>Monto:</strong> ${{ number_format($pago->amount, 2) }}</p>

    <p>Revisa en tu sistema Sofnica para procesar este pago y dar seguimiento al cliente.</p>

    <hr>
    <small>Este correo fue generado automáticamente por Sofnica.</small>
</body>
</html>
