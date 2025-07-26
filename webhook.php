
<?php
error_log("📩 CHEGOU UMA MENSAGEM");

$input = file_get_contents("php://input");
error_log("📥 INPUT: " . $input);

$data = json_decode($input, true);
error_log("📤 PARSED JSON: " . json_encode($data));

// Verifica se é mensagem válida
if (!$data || !isset($data['text']['message'])) {
    error_log("⚠️ Nada para processar.");
    http_response_code(200);
    exit("Nada recebido");
}

// 🔐 Seu token e instance ID
$token = "3859B5F2795210F1012A7FE6";
$instance = "3E401062FA83E0F253FEBE7C53096139";

// 📞 Telefone e mensagem recebida
$telefone = $data['phone'];
$mensagem = $data['text']['message'] ?? '';

// ✏️ Mensagem de resposta
$resposta = "Você disse: " . $mensagem;

// 🌐 URL da Z-API
$url = "https://api.z-api.io/instances/$instance/token/$token/send-text";

// 📦 Corpo da requisição
$payload = [
    "phone" => $telefone,
    "message" => $resposta
];

// 🚀 Envio via cURL
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    "Client-Token: $token" // ✅ Cabeçalho correto aqui
]);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

$response = curl_exec($ch);
$error = curl_error($ch);
curl_close($ch);

if ($error) {
    error_log("❌ ERRO CURL: " . $error);
} else {
    error_log("✅ RESPOSTA ZAPI: " . $response);
}

echo "ok";
