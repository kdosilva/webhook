<?php
// Configurações da Z-API
$zapi_instance = '3E401062FA83E0F253FEBE7C53096139';
$zapi_token = '021056C63BB7C732FB534BCD';

$input = file_get_contents('php://input');
$data = json_decode($input, true);

error_log("RAW INPUT: " . $input);
error_log("PARSED JSON: " . json_encode($data));

$numero = $data['phone'] ?? '';
$mensagem = $data['text']['message'] ?? '';

if (!$numero || !$mensagem) {
    echo "❌ Dados insuficientes.";
    exit;
}

$resposta = "✅ Recebi sua mensagem: \"$mensagem\"";

$url = "https://v5.z-api.io/instances/$zapi_instance/token/$zapi_token/send-text";
$body = json_encode([
    "phone" => $numero,
    "message" => $resposta
]);

$options = [
    'http' => [
        'header'  => "Content-Type: application/json",
        'method'  => 'POST',
        'content' => $body
    ]
];

$response = file_get_contents($url, false, stream_context_create($options));

if ($response === false) {
    $error = error_get_last();
    error_log("ERRO AO ENVIAR: " . print_r($error, true));
} else {
    error_log("ZAPI RESPONSE: " . $response);
}

echo "✅ Webhook processado.";

