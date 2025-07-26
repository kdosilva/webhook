<?php
require __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;

// Carregar variáveis de ambiente
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

error_log("📩 CHEGOU UMA MENSAGEM");

$input = file_get_contents("php://input");
error_log("📥 INPUT: " . $input);

$data = json_decode($input, true);
error_log("📤 PARSED JSON: " . json_encode($data));

if (!isset($data['type']) || $data['type'] !== 'ReceivedCallback' || !isset($data['text']['message'])) {
    error_log("⚠️ Nada para processar.");
    exit;
}

$mensagem = strtolower($data['text']['message']);
$telefone = $data['phone'];
$resposta = "Recebi sua mensagem: "$mensagem"";

$instance_id = $_ENV['ZAPI_INSTANCE_ID'];
$token = $_ENV['ZAPI_TOKEN'];

$url = "https://api.z-api.io/instances/$instance_id/token/$token/send-text";
$body = json_encode([
    "phone" => $telefone,
    "message" => $resposta
]);

$options = [
    'http' => [
        'header'  => "Content-type: application/json",
        'method'  => 'POST',
        'content' => $body,
    ],
];
$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);

error_log("✅ RESPOSTA ZAPI: $result");
echo "✅ Recebido";
