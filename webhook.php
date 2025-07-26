<?php
file_put_contents("log.txt", date("Y-m-d H:i:s") . " - Chegou algo\n", FILE_APPEND);
$input = file_get_contents("php://input");
file_put_contents("log.txt", $input . "\n\n", FILE_APPEND);
echo "✅ Recebido";

