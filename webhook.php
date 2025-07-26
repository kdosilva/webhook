<?php
// Configurações da Z-API
$zapi_instance = '3E401062FA83E0F253FEBE7C53096139';
$zapi_token = '021056C63BB7C732FB534BCD';

// ===== CAPTURA A MENSAGEM RECEBIDA DA Z-API =====
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Logs para o Railway
error_log("RAW INPUT: " . $input);
error_log("PARSED JSON: " . json_encode($data));

// ===== EXTRAI DADOS =====
$numero = $data['phone'] ?? '';
$mensagem = $data['text']['message'] ?? ''; // Corrigido aqui

if (!$numero || !$mensagem) {
    echo "❌ Dados insuficientes.";
    exit;
}

// ===== MONTA RESPOSTA =====
$resposta = "✅ Recebi sua mensagem: \"$mensagem\"";

// ===== ENVIA RESPOSTA COM cURL =====
$url = "https://api.z-api.io/instances/$zapi_instance/token/$zapi_token/send-text";

$payload = [
    "phone" => $numero,
    "message" => $resposta
];

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
$response = curl_exec($ch);

if (curl_errno($ch)) {
    error_log("ERRO CURL: " . curl_error($ch));
} else {
    error_log("ZAPI RESPONSE: " . $response);
}
curl_close($ch);

// ===== RESPOSTA HTTP =====
echo "✅ Webhook processado com sucesso.";


