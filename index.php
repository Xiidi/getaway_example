<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Demo de Pago - Mi Tienda</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Resumen de tu Compra</h4>
                    </div>
                    <div class="card-body">
                        <form action="procesar_pago.php" method="POST">
                            <div class="mb-3">
                                <label class="form-label">Producto:</label>
                                <input type="text" name="product_name" class="form-control" value="Suscripción Premium" readonly>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Monto a pagar:</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" step="0.01" name="amount" class="form-control" value="1.00" required>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Moneda:</label>
                                    <select name="currency" class="form-select">
                                        <option value="USD">USD</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Tu Referencia (Custom ID):</label>
                                <input type="text" name="custom_id" class="form-control" value="REF-<?php echo time(); ?>">
                            </div>
                            
                            <hr>
                            <button type="submit" class="btn btn-success w-100 btn-lg">Generar Orden y QR</button>
                        </form>
                    </div>
                </div>
                <p class="text-center mt-3 text-muted small">Al hacer clic se generará un QR vinculado al teléfono indicado.</p>
            </div>
        </div>
    </div>
</body>
</html>