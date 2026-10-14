<?php
class database {
    private PDO $pdo;
    public function __construct() {
        $host = '10.0.40.85';
        $dbname = 'examen_procesual_eda';
        $username = 'root';
        $password = '';
        $charset = 'utf8mb4';
        $dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        try {
            $this->pdo = new PDO($dsn, $username, $password, $options);
        } catch (PDOException $e) {
            throw new RuntimeException('Error de conexión a la base de datos: ' . $e->getMessage());
        }
    }
}
?>