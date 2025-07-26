<?php
error_log("CHEGOU UMA MENSAGEM");
$input = file_get_contents("php://input");
error_log("INPUT: " . $input);
echo "✅ Recebido";
