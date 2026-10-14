<?php
declare(strict_types=1);
class Nodo {
    public int $id;
    public string $nombre;
    public ?Nodo $siguiente=null;
    public ?Nodo $anterior=null;

    public function __construct(int $id, string $nombre) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->siguiente = null;
        $this->anterior = null;
    }
}
?>
