<?php
// Recebe e loga todo o conteúdo
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Loga tudo
error_log("RAW INPUT: " . $input);
error_log("PARSED JSON: " . json_encode($data));

// Responde para o navegador (ou Z-API)
if (!$data) {
    echo "❌ Nada recebido";
} else {
    echo "✅ Dados recebidos";
}
