<?php
require 'vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Captura e loga a mensagem recebida
error_log("📩 CHEGOU UMA MENSAGEM");
$input = file_get_contents("php://input");
error_log("📥 INPUT: " . $input);

$data = json_decode($input, true);
error_log("📤 PARSED JSON: " . json_encode($data));

if (!$data || !isset($data['type']) || $data['type'] !== 'ReceivedCallback') {
    error_log("⚠️ Nada para processar.");
    echo "⚠️ Ignorado";
    exit;
}

// Dados da Z-API
$instanceId = $_ENV['ZAPI_INSTANCE_ID'];
$token = $_ENV['ZAPI_TOKEN'];

// Extrai telefone e mensagem
$phone = $data['phone'] ?? null;
$message = $data['text']['message'] ?? null;

if (!$phone || !$message) {
    error_log("❌ Dados insuficientes.");
    exit;
}

// Mensagem de resposta
$resposta = "Recebido: " . $message;

// Envia resposta via Z-API com cURL
$url = "https://api.z-api.io/instances/$instanceId/token/$token/send-text";
$payload = json_encode([
    "phone" => $phone,
    "message" => $resposta
]);

$ch = curl_init($url);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
    CURLOPT_POSTFIELDS => $payload
]);

$response = curl_exec($ch);
$error = curl_error($ch);
curl_close($ch);

if ($error) {
    error_log("❌ cURL Error: $error");
} else {
    error_log("✅ RESPOSTA ZAPI: " . $response);
}

echo "✅ Mensagem processada";
