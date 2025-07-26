<?php
// Z-API configs
$zapi_instance = '3E401062FA83E0F253FEBE7C53096139'; // Ex: 3FXXXXX9F
$zapi_token = '021056C63BB7C732FB534BCD';           // Ex: abc123

// Recebe a mensagem do WhatsApp
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Extrai número e mensagem
$numero = $data['phone'] ?? '';
$mensagem = $data['message'] ?? '';

// Registra no log
file_put_contents("log_zapi.txt", json_encode($data) . PHP_EOL, FILE_APPEND);

// Verifica se há dados
if (!$numero || !$mensagem) {
    echo "Nada recebido.";
    exit;
}

// Resposta de teste
$resposta = "Olá! Recebi sua mensagem: \"$mensagem\" ✅";

// Envia via Z-API
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

// Opcional: mostrar retorno da API
file_put_contents("zapi_response.log", $response . PHP_EOL, FILE_APPEND);

echo "✅ Mensagem enviada para $numero";
