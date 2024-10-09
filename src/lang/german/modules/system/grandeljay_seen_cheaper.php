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
    'TITLE'            => 'grandeljay - Günstiger Gesehen?',
    'TEXT_TILE'        => 'Günstiger Gesehen?',
    'LONG_DESCRIPTION' => 'Zeigt einen "Günstiger gesehen?" Link auf Produktseiten an.',
    'STATUS_TITLE'     => 'Status',
    'STATUS_DESC'      => 'Wählen Sie Ja um das Modul zu aktivieren und Nein um es zu deaktivieren.',
];

foreach ($translations as $key => $text) {
    $constant = Constants::MODULE_NAME . '_' . $key;

    define($constant, $text);
}
