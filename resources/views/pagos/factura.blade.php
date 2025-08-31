<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Factura Compra #{{ $pago->id }} - Sofnica</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #374151; /* slate-700 */
            font-size: 14px;
            line-height: 1.6;
            margin: 0;
            padding: 2rem;
            background: #f9fafb; /* gray-50 */
        }
        .container {
            max-width: 600px;
            margin: auto;
            background: #ffffff;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(16,185,129,0.2); /* emerald shadow */
            border: 1px solid #d1fae5; /* emerald-100 */
        }
        h1 {
            color: #10b981; /* emerald-500 */
            font-size: 1.8rem;
            margin-bottom: 1rem;
        }
        .info p {
            margin: 0.5rem 0;
        }
        .footer {
            margin-top: 2rem;
            font-size: 12px;
            text-align: center;
            color: #6b7280; /* gray-500 */
        }
        .logo {
            width: 120px;
            margin-bottom: 1rem;
        }
        .header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .details {
            border-top: 1px solid #d1fae5;
            border-bottom: 1px solid #d1fae5;
            padding: 1rem 0;
            margin: 1rem 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            
            @if (file_exists(public_path('images/logo-sofnica.png')))
              <img src="{{ public_path('images/logo-sofnica.png') }}" alt="Sofnica" class="logo">
            @endif

            <h1>Factura #{{ $pago->id }}</h1>
            <p><strong>Sofnica - Soluciones en Software</strong></p>
        </div>

        <div class="info">
            <p><strong>Comprador:</strong> {{ $pago->payer_email }}</p>
        <p><strong>Fecha de compra:</strong> {{ $pago->created_at->setTimezone('America/Managua')->format('d/m/Y H:i') }}</p>

        </div>

        <div class="details">
      <p><strong>Concepto:</strong> {{ $pago->description }}</p>

            <p><strong>Monto pagado:</strong> ${{ number_format($pago->amount, 2) }} USD</p>
            <p><strong>Referencia PayPal:</strong> {{ $pago->paypal_order_id }}</p>
        </div>

        <p>¡Gracias por tu confianza en <strong>Sofnica</strong>! Si necesitas soporte o quieres ampliar funcionalidades,
        contáctanos directamente por WhatsApp al <strong>+505 8542 9144</strong>.</p>

        <div class="footer">
            Sofnica © {{ now()->year }} - Todos los derechos reservados<br>
            www.sofnica.info
        </div>
    </div>
</body>
</html>
