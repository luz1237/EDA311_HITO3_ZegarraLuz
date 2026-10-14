<?php
declare(strict_types=1);
class ListaDoblementeEnlazada {
    private ?Nodo $cabeza = null; 
    private ?Nodo $cola = null;   
    private int $tamanyo = 0;     

    public function agregarAlFinal(Nodo $nodo): void {
        if ($this->cabeza === null) {
            $this->cabeza = $nodo;
            $this->cola = $nodo;
        } else {
            $this->cola->siguiente = $nodo; 
            $nodo->anterior = $this->cola;  
            $this->cola = $nodo;            
        }
        $this->tamanyo++;
    }

    public function obtenerTodos(): array {
        $registros = [];
        $actual = $this->cabeza; 
        while ($actual !== null) {
            $registros[] = $actual->datos;
            $actual = $actual->siguiente; 
        }
        return $registros;
    }

    public function ordenarBurbuja(string $campo, bool $ascendente = true): void {
        if ($this->tamanyo < 2) {
            return;
        }

        $fin = null; 

        do {
            $huboCambio = false;
            $actual = $this->cabeza;

            while ($actual->siguiente !== $fin) {
                $vecino = $actual->siguiente;

                $resultado = $this->comparar($actual->datos[$campo], $vecino->datos[$campo]);
                if (!$ascendente) {
                    $resultado = -$resultado; 
                }

                if ($resultado > 0) {
                    $auxiliar = $actual->datos;
                    $actual->datos = $vecino->datos;
                    $vecino->datos = $auxiliar;
                    $huboCambio = true;
                }

                $actual = $vecino; 
            }

            $fin = $actual;

        } while ($huboCambio && $fin !== $this->cabeza);
    }

    private function comparar($a, $b): int {
        if (is_numeric($a) && is_numeric($b)) {
            return (float)$a <=> (float)$b;
        }
        return strcasecmp((string)$a, (string)$b);
    }
}