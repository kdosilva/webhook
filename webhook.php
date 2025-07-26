<?php
// Configurações da Z-API
$zapi_instance = '3E401062FA83E0F253FEBE7C53096139';
$zapi_token = '021056C63BB7C732FB534BCD';

// Recebe e interpreta os dados da Z-API
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Log para debug (Railway logs)
error_log("RAW INPUT: " . $input);
error_log("PARSED JSON: " . json_encode($data));

// Extrai dados corretos
$numero = $data['phone'] ?? '';
$mensagem = $data['text']['message'] ?? ''; // <- CORRIGIDO AQUI

if (!$numero || !$mensagem) {
    echo "❌ Dados insuficientes.";
    exit;
}

// Monta a resposta
$resposta = "✅ Recebi sua mensagem: \"$mensagem\"";

// Envia de volta via Z-API
$url = "https://api.z-api.io/instances/$zapi_instance/token/$zapi_token/send-text";
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

// Loga resposta da Z-API
error_log("ZAPI RESPONSE: " . $response);

echo "✅ Webhook processado.";
