<?php
// Z-API configs
$zapi_instance = '3E401062FA83E0F253FEBE7C53096139'; // Ex: 3FXXXXX9F
$zapi_token = '021056C63BB7C732FB534BCD';           // Ex: abc123

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

