<?php
// success.php
// Asume que se recibe la respuesta del servicio de pagos como query string

// Verificar que exista la información esperada
$type = $_GET['type'] ?? '';
$code = $_GET['message']['code'] ?? null;

// Si no es una respuesta exitosa, mostrar error
if ($type !== 'success' || $code != 200) {
    $error = true;
    $error_message = 'La transacción no se pudo completar correctamente.';
} else {
    $error = false;
    // Extraer datos
    $data = $_GET['data'] ?? [];
    $token = $data['token'] ?? '';
    $trx_id = $data['trx_id'] ?? '';
    $amount = $data['amount'] ?? '0.00';
    $custom = $data['custom'] ?? '';
    $payer = $data['payer'] ?? [];
    $payer_name = $payer['username'] ?? '';
    $payer_email = $payer['email'] ?? '';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Confirmación de Pago</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background: #f5f7fa;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }
        .card {
            max-width: 500px;
            width: 100%;
            background: white;
            border-radius: 24px;
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1), 0 8px 10px -6px rgba(0,0,0,0.02);
            overflow: hidden;
            text-align: center;
            padding: 2rem 1.5rem;
        }
        .icon {
            width: 80px;
            height: 80px;
            background: #10b981;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
        }
        .icon svg {
            width: 48px;
            height: 48px;
            stroke: white;
            stroke-width: 2;
            fill: none;
        }
        .icon.error {
            background: #ef4444;
        }
        h1 {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: #1f2937;
        }
        .subtitle {
            color: #6b7280;
            margin-bottom: 1.5rem;
            font-size: 0.95rem;
        }
        .details {
            background: #f9fafb;
            border-radius: 16px;
            padding: 1.25rem;
            text-align: left;
            margin: 1.5rem 0;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-bottom: 1px solid #e5e7eb;
            font-size: 0.9rem;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .detail-label {
            font-weight: 500;
            color: #4b5563;
        }
        .detail-value {
            color: #1f2937;
            font-weight: 500;
            word-break: break-all;
            text-align: right;
            max-width: 60%;
        }
        .button {
            display: inline-block;
            background: #3b82f6;
            color: white;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            border-radius: 40px;
            text-decoration: none;
            transition: background 0.2s;
            margin-top: 1rem;
        }
        .button:hover {
            background: #2563eb;
        }
        .footnote {
            font-size: 0.75rem;
            color: #9ca3af;
            margin-top: 1.5rem;
        }
    </style>
</head>
<body>
    <div class="card">
        <?php if ($error): ?>
            <div class="icon error">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </div>
            <h1>Error</h1>
            <p class="subtitle"><?php echo htmlspecialchars($error_message); ?></p>
            <a href="javascript:history.back()" class="button">Intentar de nuevo</a>
        <?php else: ?>
            <div class="icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <h1>¡Pago exitoso!</h1>
            <p class="subtitle">Tu transacción se ha completado correctamente.</p>

            <div class="details">
                <div class="detail-row">
                    <span class="detail-label">Monto:</span>
                    <span class="detail-value">$<?php echo number_format((float)$amount, 2); ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">ID de transacción:</span>
                    <span class="detail-value"><?php echo htmlspecialchars($trx_id); ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Referencia:</span>
                    <span class="detail-value"><?php echo htmlspecialchars($custom); ?></span>
                </div>
                <?php if ($payer_name): ?>
                <div class="detail-row">
                    <span class="detail-label">Pagador:</span>
                    <span class="detail-value"><?php echo htmlspecialchars($payer_name); ?></span>
                </div>
                <?php endif; ?>
                <?php if ($payer_email): ?>
                <div class="detail-row">
                    <span class="detail-label">Correo:</span>
                    <span class="detail-value"><?php echo htmlspecialchars($payer_email); ?></span>
                </div>
                <?php endif; ?>
                <div class="detail-row">
                    <span class="detail-label">Token:</span>
                    <span class="detail-value"><?php echo htmlspecialchars($token); ?></span>
                </div>
            </div>

            <!-- Botón para volver al comercio o a la app Flutter -->
            <a href="javascript:void(0)" class="button" id="closeButton">Cerrar ventana</a>
            <p class="footnote">Recibirás un correo de confirmación en breve.</p>
        <?php endif; ?>
    </div>

    <script>
        // Si se abre en un WebView de Flutter, podemos enviar un mensaje al host
        // Si es navegador, cierra la ventana (si fue abierta como popup)
        document.getElementById('closeButton')?.addEventListener('click', function() {
            if (window.flutter_inappwebview) {
                // Si está dentro de un WebView de Flutter, puedes llamar a un método de JavaScript bridge
                // Por ejemplo, asumiendo que tienes un canal llamado 'closeWebView'
                window.flutter_inappwebview.callHandler('closeWebView');
            } else {
                window.close(); // Intenta cerrar la ventana (funciona si fue abierta con window.open)
                // Si no, redirige a una URL de tu app (deeplink)
                // window.location.href = 'tuapp://'; 
            }
        });
    </script>
</body>
</html>