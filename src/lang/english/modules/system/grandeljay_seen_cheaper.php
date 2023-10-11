<?php

/**
 * Seen Cheaper
 *
 * @author  Jay Trees <modified-seen-cheaper@grandels.email>
 * @link    https://github.com/grandeljay/modified-seen-cheaper
 * @package GrandeljaySeenCheaper
 */

use Grandeljay\SeenCheaper\Constants;

$translations = array(
    /** Module */
    'TITLE'            => 'grandeljay - Cheaper Seen?',
    'TEXT_TILE'        => 'Seen cheaper?',
    'LONG_DESCRIPTION' => 'Displays a "Seen cheaper?" link on product pages.',
    'STATUS_TITLE'     => 'Status',
    'STATUS_DESC'      => 'Select Yes to activate the module and No to deactivate it.',
);

foreach ($translations as $key => $text) {
    $constant = Constants::MODULE_NAME . '_' . $key;

    define($constant, $text);
}
