<?php
declare(strict_types=1);

class RegistroController {
    private string $t; 
    private array $config;
    private Registro $modelo;

    public function __construct(PDO $pdo, string $t, array $config) {
        $this->t = $t;
        $this->config = $config;
        $this->modelo = new Registro(
            $pdo,
            $t,
            $config['pk'],
            array_keys($config['campos'])
        );
    }

    public function listar(): void {
        $t = $this->t;
        $pk = $this->config['pk'];
        $campos = $this->config['campos'];
        $msg = $_GET['msg'] ?? '';

        $columnas = array_merge([$pk], array_keys($campos));

        $ordenCampo = $_GET['orden'] ?? $this->config['orden'];
        if (!in_array($ordenCampo, $columnas, true)) {
            $ordenCampo = $this->config['orden'];
        }
        $asc = ($_GET['dir'] ?? 'asc') !== 'desc';

        $editar = null;
        if (isset($_GET['editar'])) {
            $editar = $this->modelo->buscar($_GET['editar']);
        }

        $lista = $this->modelo->obtenerLista();
        $sinOrdenar = $lista->obtenerTodos();

        $lista->ordenarBurbuja($ordenCampo, $asc);
        $ordenados = $lista->obtenerTodos();

        require 'views/registros.php';
    }

    public function guardar(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?t=' . $this->t);
            exit;
        }

        try {
            $error = $this->validar();

            if ($error !== '') {
                $msg = 'Error: ' . $error;
            } else {
                $valores = [];
                foreach ($this->config['campos'] as $nombre => $tipo) {
                    $valores[] = trim($_POST[$nombre]);
                }

                if (($_POST['accion'] ?? '') === 'modificar') {
                    $this->modelo->actualizar($_POST['id'], $valores);
                    $msg = 'Registro modificado.';
                } else {
                    $this->modelo->crear($valores);
                    $msg = 'Registro creado.';
                }
            }
        } catch (PDOException $e) {
            $msg = 'Error en la base de datos: ' . $e->getMessage();
        }

        header('Location: index.php?t=' . $this->t . '&msg=' . urlencode($msg));
        exit;
    }

    public function eliminar(): void {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
                $this->modelo->eliminar($_POST['id']);
                $msg = 'Registro eliminado.';
            } else {
                $msg = 'Error: solicitud no válida.';
            }
        } catch (PDOException $e) {
            $msg = 'No se pudo eliminar: ' . $e->getMessage();
        }

        header('Location: index.php?t=' . $this->t . '&msg=' . urlencode($msg));
        exit;
    }

    private function validar(): string {
        foreach ($this->config['campos'] as $nombre => $tipo) {
            $valor = isset($_POST[$nombre]) ? trim($_POST[$nombre]) : '';

            if ($valor === '') {
                return "El campo $nombre es obligatorio.";
            }
            if ($tipo === 'number' && !is_numeric($valor)) {
                return "El campo $nombre debe ser numérico.";
            }
            if ($tipo === 'email' && !filter_var($valor, FILTER_VALIDATE_EMAIL)) {
                return "El campo $nombre no es un email válido.";
            }
        }
        return '';
    }
}