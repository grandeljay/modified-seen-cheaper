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
    'TITLE'            => 'grandeljay - Visto più economico?',
    'TEXT_TILE'        => 'Ha visto un prezzo più basso?',
    'LONG_DESCRIPTION' => 'Visualizza un link "Visto più conveniente? sulle pagine dei prodotti.',
    'STATUS_TITLE'     => 'Stato',
    'STATUS_DESC'      => 'Selezioni Sì per attivare il modulo e No per disattivarlo.',
];

foreach ($translations as $key => $text) {
    $constant = Constants::MODULE_NAME . '_' . $key;

    define($constant, $text);
}
