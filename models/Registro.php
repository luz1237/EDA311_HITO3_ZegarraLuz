<?php
declare(strict_types=1);

class Registro {
    private PDO $pdo;
    private string $tabla;
    private string $pk;
    private array $campos; 

    public function __construct(PDO $pdo, string $tabla, string $pk, array $campos) {
        $this->pdo = $pdo;
        $this->tabla = $tabla;
        $this->pk = $pk;
        $this->campos = $campos;
    }

    public function obtenerLista(): ListaDoblementeEnlazada {
        $stmt = $this->pdo->query("SELECT * FROM `{$this->tabla}`"); 
        $filas = $stmt->fetchAll(); 

        $lista = new ListaDoblementeEnlazada();
        foreach ($filas as $fila) {
            $nodo = new Nodo($fila);
            $lista->agregarAlFinal($nodo);
        }
        return $lista;
    }

    public function buscar($id): ?array {
        $stmt = $this->pdo->prepare("SELECT * FROM `{$this->tabla}` WHERE `{$this->pk}` = ?");
        $stmt->execute([$id]);
        $fila = $stmt->fetch();
        return $fila === false ? null : $fila;
    }

    public function crear(array $valores): void {
        $columnas = '`' . implode('`, `', $this->campos) . '`';
        $signos = implode(', ', array_fill(0, count($this->campos), '?'));

        $stmt = $this->pdo->prepare("INSERT INTO `{$this->tabla}` ($columnas) VALUES ($signos)");
        $stmt->execute($valores);
    }

    public function actualizar($id, array $valores): void {
        $partes = [];
        foreach ($this->campos as $campo) {
            $partes[] = "`$campo` = ?";
        }

        $sql = "UPDATE `{$this->tabla}` SET " . implode(', ', $partes) . " WHERE `{$this->pk}` = ?";
        $valores[] = $id; 

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($valores);
    }

    public function eliminar($id): void {
        $stmt = $this->pdo->prepare("DELETE FROM `{$this->tabla}` WHERE `{$this->pk}` = ?");
        $stmt->execute([$id]);
    }
}