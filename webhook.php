<?php
// ===== CONFIGURAÇÕES DA SUA INSTÂNCIA Z-API E OPENAI =====
$zapi_instance = '3E401062FA83E0F253FEBE7C53096139'; // Ex: 3E401062FA83E0F253FEBE7C53096139
$zapi_token = '021056C63BB7C732FB534BCD';           // Ex: 021056C63BB7C732FB534BCD
$openai_api_key = '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'; // sua chave secreta da OpenAI

// ===== CAPTURA A MENSAGEM RECEBIDA =====
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// LOG para debug no Railway
error_log("RAW INPUT: " . $input);
error_log("PARSED JSON: " . json_encode($data));

// ===== EXTRAI O NÚMERO E A MENSAGEM =====
$numero = $data['phone'] ?? '';
$mensagem = $data['text']['message'] ?? '';

if (!$numero || !$mensagem) {
    echo "❌ Dados insuficientes.";
    exit;
}

// ===== ENVIA A MENSAGEM PARA O CHATGPT =====
$ch = curl_init("https://api.openai.com/v1/chat/completions");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $openai_api_key",
    "Content-Type: application/json"
]);

$openai_payload = [
    "model" => "gpt-3.5-turbo",
    "messages" => [
        ["role" => "system", "content" => "Você é um assistente financeiro no WhatsApp."],
        ["role" => "user", "content" => $mensagem]
    ],
    "temperature" => 0.7
];
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($openai_payload));
$openai_response = curl_exec($ch);
curl_close($ch);

// ===== PEGA A RESPOSTA DA IA =====
$resposta = "Desculpe, não consegui responder no momento.";
$data_response = json_decode($openai_response, true);
if (isset($data_response['choices'][0]['message']['content'])) {
    $resposta = trim($data_response['choices'][0]['message']['content']);
}

error_log("OPENAI: " . $resposta);

// ===== ENVIA RESPOSTA VIA WHATSAPP (Z-API) =====
$url = "https://api.z-api.io/instances/$zapi_instance/token/$zapi_token/send-text";
$payload = [
    "phone" => $numero,
    "message" => $resposta
];

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "Client-Token: $zapi_token"
]);
$response = curl_exec($ch);

if (curl_errno($ch)) {
    error_log("ERRO CURL: " . curl_error($ch));
} else {
    error_log("ZAPI RESPONSE: " . $response);
}
curl_close($ch);

// ===== RESPOSTA PARA Z-API =====
echo "✅ Processado com ChatGPT";

