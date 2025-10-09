<?php
require_once 'vendor/autoload.php';

use GuzzleHttp\Client;

try {
    // Instancia del cliente HTTP
    $client = new Client();

    // Datos del mensaje
    $payload = [
        "message" => "Hola, yeferson, Tu código de verificación es: *1234* \n" . " En Moda Oxford S.A.S., valoramos profundamente la confianza que depositas en nosotros. Por eso queremos invitarte a autorizar el tratamiento de tus datos personales, conforme a nuestra política 👉 https://www.oxfordjeans.com/terminos/tratamiento-de-datos Por seguridad, para autenticar tu identidad y completar la autorización, ingresa el código",
        "wa_id" => "573103909483", // número de destino con código de país
        "from_id" => 10279         // ID del remitente asignado por Wasapi
    ];

    // Petición POST
    $response = $client->request('POST', 'https://api-ws.wasapi.io/api/v1/whatsapp-messages', [
        'headers' => [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer 158523|0WskukcpPWTDxNpS7xxGnLLG9kquX9qF4KAvHlIL',
            'Content-Type' => 'application/json',
        ],
        'body' => json_encode($payload)
    ]);

    // Mostrar respuesta del servidor
    echo "✅ Envío exitoso:\n";
    echo $response->getBody()->getContents();

} catch (\GuzzleHttp\Exception\RequestException $e) {
    echo "❌ Error al enviar mensaje:\n";
    if ($e->hasResponse()) {
        echo $e->getResponse()->getBody()->getContents();
    } else {
        echo $e->getMessage();
    }
}
