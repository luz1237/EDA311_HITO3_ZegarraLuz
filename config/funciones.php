<?php
declare(strict_types=1);

// Escapa el texto para mostrarlo seguro en HTML
function h($texto): string {
    return htmlspecialchars((string)$texto, ENT_QUOTES, 'UTF-8');
}