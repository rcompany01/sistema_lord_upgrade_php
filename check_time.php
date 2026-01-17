<?php
// check_time.php - Diagnostico de Performance
$start = microtime(true);

echo "<h3>Diagnostico de Performance</h3>";

// 1. Teste de Require
require_once('class/DB.class.php');
$t1 = microtime(true);
echo "Carregar DB.class.php: " . number_format($t1 - $start, 4) . "s<br>";

// 2. Teste de Conexao
$db = DB::getInstance();
$conn = $db->getConnection();
$t2 = microtime(true);
echo "Conectar ao MySQL: " . number_format($t2 - $t1, 4) . "s<br>";

// 3. Teste de Query
$id = 4; // Um ID valido
$stmt = $conn->prepare("SELECT * FROM prestadores WHERE id_prest=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$t3 = microtime(true);

echo "Executar Query (SELECT * ... id=4): " . number_format($t3 - $t2, 4) . "s<br>";

if ($row) {
    echo "Dados recuperados: " . $row['nome_prest'] . "<br>";
    // Verifica caminho da foto
    echo "Caminho da Foto (DB): " . $row['foto_prest'] . "<br>";
} else {
    echo "Nenhum prestador encontrado com ID 4.<br>";
}

echo "<strong>Tempo Total: " . number_format(microtime(true) - $start, 4) . "s</strong>";
?>