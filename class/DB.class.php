<?php
class DB
{
    private static $instance = null;
    private $conn;

    private $host = 'localhost';
    private $user = 'grupolordeventos_teste_lordsis';
    private $pass = '{6Ebq;+Xt(9E)uJ%';
    private $dbname = 'grupolordeventos_teste_lordsis';

    private function __construct()
    {
        try {
            // Utilizando mysqli com tratamento de exceções
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
            $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->dbname);
            $this->conn->set_charset("utf8");
        } catch (Exception $e) {
            die("Erro de conexão com o banco de dados: " . $e->getMessage());
        }
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new DB();
        }
        return self::$instance;
    }

    public function getConnection()
    {
        return $this->conn;
    }
}
?>