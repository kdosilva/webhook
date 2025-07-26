<?php
// Loga quando chega algo
error_log("📩 CHEGOU UMA MENSAGEM");

// Captura e mostra o corpo da requisição
$input = file_get_contents("php://input");
error_log("📥 INPUT: " . $input);

// Decodifica JSON
$data = json_decode($input, true);
error_log("📤 PARSED JSON: " . json_encode($data));

if (!$data || !isset($data['text']['message'])) {
    error_log("⚠️ Nada para processar.");
    http_response_code(200);
    exit("Nada recebido");
}

// Pega telefone e mensagem
$telefone = $data['phone'];
$mensagem = $data['text']['message'] ?? '';

// ✅ Monte a resposta da IA ou resposta padrão
$resposta = "Você disse: " . $mensagem;

// Envia mensagem de volta usando Z-API
$token = "021056C63BB7C732FB534BCD";
$instance = "3E401062FA83E0F253FEBE7C53096139";

// Monta o payload
$payload = [
    "phone" => $telefone,
    "message" => $resposta
];

// Configura a requisição CURL
$url = "https://api.z-api.io/instances/{$instance}/token/{$token}/send-text";
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

// Executa e loga a resposta
$response = curl_exec($ch);
$error = curl_error($ch);
curl_close($ch);

if ($error) {
    error_log("❌ ERRO CURL: " . $error);
} else {
    error_log("✅ RESPOSTA ZAPI: " . $response);
}

echo "ok";
