
<?php
require_once('vendor/autoload.php');

use GuzzleHttp\Client;

$base_url = "https://xiidi.test/pay/api/v1/";

$client = new Client(['verify' => false]);

try {
    
    $authResponse = $client->post($base_url . 'authentication/token', [
        'json' => [
            'client_id' => "tRCDXCuztQzRYThPwlh1KXAYm4bG3rwWjbxM2R63kTefrGD2B9jNn6JnarDf7ycxdzfnaroxcyr5cnduY6AqpulRSebwHwRmGerA",
            'secret_id' => "oZouVmqHCbyg6ad7iMnrwq3d8wy9Kr4bo6VpQnsX6zAOoEs4oxHPjttpun36JhGxDl7AUMz3ShUqVyPmxh4oPk3TQmDF7YvHN5M3",
        ],
        'headers' => ['accept' => 'application/json']
    ]);

    
    $authData = json_decode($authResponse->getBody(), true);
    $access_token = $authData['data']['access_token'] ?? '';

    if (empty($access_token)) {
        throw new Exception("No se pudo capturar el Access Token");
    }

    $paymentResponse = $client->post($base_url . 'payment/create', [
        'json' => [
            'amount'     => number_format($_POST['amount'], 2, '.', ''), // Validated amount
            'currency'   => "USD",
            'return_url' => "https://webhook.site/486f6e58-f919-458a-a5d0-0ab043b87e51",
            'cancel_url' => "https://getaway.test/cancel.php",
            'custom'     => bin2hex(random_bytes(5)), // Simula tu custom_random_string
        ],
        'headers' => [
            'Authorization' => 'Bearer ' . $access_token,
            'accept'        => 'application/json',
            'content-type'  => 'application/json',
        ],
    ]);

    $result = json_decode($paymentResponse->getBody(), true);

    if (isset($result['data']['payment_url'])) {
        $payment_url = $result['data']['payment_url'];
        ?>
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <title>Escanear para Pagar</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        </head>
        <body class="bg-light">
            <div class="container mt-5">
                <div class="row justify-content-center">
                    <div class="col-md-5 text-center">
                        <div class="card shadow">
                            <div class="card-body">
                                <h3 class="mb-4">link para Pagar</h3>
                                <a href="<?php echo $payment_url; ?>" class="btn btn-outline-primary btn-sm">O pagar mediante Login manual</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </body>
        </html>
        <?php
    } else {
        throw new Exception("Error en la API: " . json_encode($result));
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
