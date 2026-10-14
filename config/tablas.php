<?php
declare(strict_types=1);
return [
    'clientes' => [
        'pk' => 'id_cliente',
        'campos' => [
            'nombre'   => 'text',
            'email'    => 'email',
            'telefono' => 'text',
        ],
        'orden' => 'nombre',
    ],
    'productos' => [
        'pk' => 'id_producto',
        'campos' => [
            'nombre' => 'text',
            'precio' => 'number',
            'stock'  => 'number',
        ],
        'orden' => 'precio',
    ],
    'pedidos' => [
        'pk' => 'id_pedido',
        'campos' => [
            'id_cliente' => 'number', 
            'fecha'      => 'date',
            'total'      => 'number',
        ],
        'orden' => 'fecha',
    ],
];