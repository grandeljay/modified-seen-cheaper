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
    'TITLE'            => 'grandeljay - Moins cher Vu ?',
    'TEXT_TILE'        => 'Moins cher Vu ?',
    'LONG_DESCRIPTION' => 'Affiche un lien "Vu moins cher ? Affiche un lien vers les pages de produits.',
    'STATUS_TITLE'     => 'Statut',
    'STATUS_DESC'      => 'Sélectionnez Oui pour activer le module et Non pour le désactiver.',
);

foreach ($translations as $key => $text) {
    $constant = Constants::MODULE_NAME . '_' . $key;

    define($constant, $text);
}
