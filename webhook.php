<?php
// webhook.php (responde com texto fixo para teste)

// Configurações da Z-API
$zapi_instance = 'SUA_INSTANCIA_ID'; // exemplo: 3FXXXXX9F
$zapi_token = 'SEU_TOKEN';           // exemplo: abcd1234

// Recebe os dados do webhook
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Extrai número e mensagem
$numero = $data['phone'] ?? '';
$mensagem = $data['message'] ?? '';

if (!$numero || !$mensagem) {
    file_put_contents('log_erro.txt', 'Requisição sem número ou mensagem' . PHP_EOL, FILE_APPEND);
    exit;
}

// Resposta de teste
$resposta = "Olá! Mensagem recebida com sucesso! 🚀";

// Envia a resposta via Z-API
$url = "https://api.z-api.io/instances/$zapi_instance/token/$zapi_token/send-text";
$body = json_encode([
    'phone' => $numero,
    'message' => $resposta
]);

$options = [
    'http' => [
        'method'  => 'POST',
        'header'  => "Content-Type: application/json",
        'content' => $body,
        'ignore_errors' => true
    ]
];
$response = file_get_contents($url, false, stream_context_create($options));

// Registra o log da resposta
file_put_contents('zapi_response.log', $response . PHP_EOL, FILE_APPEND);
