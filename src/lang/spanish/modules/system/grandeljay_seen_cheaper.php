<?php

/**
 * Seen Cheaper
 *
 * @author  Jay Trees <modified-seen-cheaper@grandels.email>
 * @link    https://github.com/grandeljay/modified-seen-cheaper
 * @package GrandeljaySeenCheaper
 */

use Grandeljay\SeenCheaper\Constants;

$translations = [
    /** Module */
    'TITLE'            => 'grandeljay - ¿Se ve más barato?',
    'TEXT_TILE'        => '¿Lo ve más barato?',
    'LONG_DESCRIPTION' => 'Muestra un enlace "¿Visto más barato?" en las páginas de productos.',
    'STATUS_TITLE'     => 'Estado',
    'STATUS_DESC'      => 'Seleccione Sí para activar el módulo y No para desactivarlo.',
];

foreach ($translations as $key => $text) {
    $constant = Constants::MODULE_NAME . '_' . $key;

    define($constant, $text);
}
