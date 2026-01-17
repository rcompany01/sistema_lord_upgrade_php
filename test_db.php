<?php
// Arquivo de teste de conexao isolado
// Por favor, acesse via navegador: https://teste.grupolordeventos.com.br/test_db.php

$host = 'localhost';
$user = 'grupolordeventos_teste_lordsis';
$pass = '{6Ebq;+Xt(9E)uJ%';
$dbname = 'grupolordeventos_teste';

echo "<h2>Tentando conectar ao MySQL...</h2>";
echo "<p>Host: $host</p>";
echo "<p>User: $user</p>";
echo "<p>Pass (inicio): " . substr($pass, 0, 3) . "***</p>";

try {
    // Tenta conectar
    $mysqli = new mysqli($host, $user, $pass, $dbname);

    if ($mysqli->connect_error) {
        die("Falha na conexao: " . $mysqli->connect_error);
    }

    echo "<h3 style='color:green'>SUCESSO! Conexão estabelecida com sucesso via localhost.</h3>";
    echo "Informacoes do Host: " . $mysqli->host_info;
    $mysqli->close();

} catch (Exception $e) {
    echo "<h3 style='color:red'>ERRO FATAL:</h3>";
    echo $e->getMessage();
}
?>