<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    error_log("❌ Método inválido: " . $_SERVER['REQUEST_METHOD']);
    http_response_code(405);
    exit("❌ Método inválido");
}

$input = str_replace('";:', '":', file_get_contents("php://input"));
error_log("CHEGOU UMA MENSAGEM");
error_log("INPUT: " . $input);

$data = json_decode($input, true);
error_log("PARSED JSON: " . json_encode($data));

// Aqui você pode adicionar sua resposta automática via Z-API
echo "✅ Recebido";
http_response_code(200);
exit;
