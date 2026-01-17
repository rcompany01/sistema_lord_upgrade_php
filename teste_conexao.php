<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>Teste de Ambiente e Conexão</h1>";
echo "<strong>Data/Hora:</strong> " . date('Y-m-d H:i:s') . "<br>";
echo "<strong>PHP Version:</strong> " . phpversion() . "<br>";

echo "<hr>";

try {
    $dbFile = __DIR__ . '/class/DB.class.php';
    if (!file_exists($dbFile)) {
        throw new Exception("Arquivo class/DB.class.php não encontrado em: $dbFile");
    }

    echo "Incluindo arquivo de conexão... <br>";
    require_once($dbFile);

    echo "Tentando instanciar a classe DB... <br>";
    $db = DB::getInstance();

    echo "Obtendo conexão... <br>";
    $conn = $db->getConnection();

    if ($conn instanceof mysqli) {
        echo "<h2 style='color:green'>SUCESSO: Conexão com o Banco de Dados estabelecida!</h2>";
        echo "<strong>Host Info:</strong> " . $conn->host_info . "<br>";
        echo "<strong>Server Info:</strong> " . $conn->server_info . "<br>";

        // Teste simples de query
        $result = $conn->query("SELECT 1");
        if ($result) {
            echo "Query de teste (SELECT 1) executada com sucesso.";
        } else {
            echo "Aviso: Falha ao executar query simples.";
        }
    } else {
        echo "<h2 style='color:red'>ERRO: O objeto retornado não é uma conexão MySQLi válida.</h2>";
        var_dump($conn);
    }

} catch (mysqli_sql_exception $e) {
    echo "<h2 style='color:red'>Erro de Conexão MySQL:</h2>";
    echo "<strong>Mensagem:</strong> " . $e->getMessage() . "<br>";
    echo "<strong>Código:</strong> " . $e->getCode() . "<br>";
} catch (Exception $e) {
    echo "<h2 style='color:red'>Erro Geral:</h2>";
    echo $e->getMessage();
} catch (Error $e) {
    echo "<h2 style='color:red'>Erro Fatal PHP:</h2>";
    echo "<strong>Mensagem:</strong> " . $e->getMessage() . "<br>";
    echo "<strong>Arquivo:</strong> " . $e->getFile() . "<br>";
    echo "<strong>Linha:</strong> " . $e->getLine() . "<br>";
}
?>