<?php

/**
 * Seen Cheaper
 *
 * @author  Jay Trees <modified-seen-cheaper@grandels.email>
 * @link    https://github.com/grandeljay/modified-seen-cheaper
 * @package GrandeljaySeenCheaper
 */

defined('DISPLAY_PRIVACY_CHECK') or define('DISPLAY_PRIVACY_CHECK', 'true');

require_once DIR_WS_LANGUAGES . $_SESSION['language'] . '/grandeljay_seen_cheaper.php';
require_once DIR_FS_INC . 'parse_multi_language_value.inc.php';
require_once DIR_FS_INC . 'secure_form.inc.php';
require_once DIR_FS_INC . 'xtc_validate_email.inc.php';

if (file_exists(DIR_FS_CATALOG . 'templates/' . CURRENT_TEMPLATE . '/source/inc/css_button.inc.php')) {
    require_once 'templates/' . CURRENT_TEMPLATE . '/source/inc/css_button.inc.php';
}

$action  = isset($_GET ['action']) ? $_GET['action'] : '';
$privacy = isset($_POST['privacy']);

if (!isset($smarty) || !is_object($smarty)) {
    $smarty = new Smarty();
}

if (!isset($main) || !is_object($main)) {
    $main = new main();
}

$error = false;

/**
 * Send
 */
if ('send' === $action) {
    $valid_params = array(
        'name',
        'email',
        'phone',
        'message_body',

        'competitor_url',
        'competitor_price',
    );

    foreach ($_POST as $key => $value) {
        if ((!isset(${$key}) || !is_object(${$key})) && in_array($key, $valid_params)) {
            ${$key} = xtc_db_prepare_input($value);
        }
    }

    global $messageStack;

    if (!xtc_validate_email(trim($email))) {
        $messageStack->add('seen-cheaper', ERROR_EMAIL);
        $error = true;
    }

    if ('' === trim($message_body)) {
        $messageStack->add('seen-cheaper', ERROR_MSG_BODY);
        $error = true;
    }

    if (empty($competitor_url)) {
        $messageStack->add('seen-cheaper', ERROR_COMPETITOR_URL);
        $error = true;
    }

    if (empty($competitor_price)) {
        $messageStack->add('seen-cheaper', ERROR_COMPETITOR_PRICE);
        $error = true;
    }

    if ('true' === DISPLAY_PRIVACY_CHECK && empty($privacy)) {
        $messageStack->add('seen-cheaper', ENTRY_PRIVACY_ERROR);
        $error = true;
    }

    if (false === check_secure_form($_POST)) {
        $messageStack->add('seen-cheaper', ENTRY_TOKEN_ERROR);
        $error = true;
    }

    if ($messageStack->size('seen-cheaper') > 0) {
        $messageStack->add('seen-cheaper', ERROR_MAIL);
        $smarty->assign('error_message', $messageStack->output('seen-cheaper'));
    }

    if (false === $error) {
        $email_template_html = '/mail/' . $_SESSION['language'] . '/grandeljay_seen_cheaper.html';
        $email_template_txt  = '/mail/' . $_SESSION['language'] . '/grandeljay_seen_cheaper.txt';

        $product_name  = '';
        $product_model = '';
        $product_link  = '';

        if (isset($_POST['products_id'])) {
            $product       = new product($_POST['products_id']);
            $product->data = $product->buildDataArray($product->data);

            $product_name  = $product->data['PRODUCTS_NAME'];
            $product_model = $product->data['PRODUCTS_MODEL'];
            $product_link  = $product->data['PRODUCTS_LINK'];
        }

        if (
            file_exists(DIR_FS_DOCUMENT_ROOT . 'templates/' . CURRENT_TEMPLATE . $email_template_html)
            && file_exists(DIR_FS_DOCUMENT_ROOT . 'templates/' . CURRENT_TEMPLATE . $email_template_txt)
        ) {
            $smarty->assign('language', $_SESSION['language']);
            $smarty->assign('tpl_path', HTTP_SERVER . DIR_WS_CATALOG . 'templates/' . CURRENT_TEMPLATE . '/');
            $smarty->assign('logo_path', HTTP_SERVER . DIR_WS_CATALOG . 'templates/' . CURRENT_TEMPLATE . '/img/');

            $smarty->assign('PRODUCT_NAME', $product_name);
            $smarty->assign('PRODUCT_MODEL', $product_model);
            $smarty->assign('PRODUCT_LINK', $product_link);

            $smarty->assign('COMPETITOR_URL', $competitor_url);
            $smarty->assign('COMPETITOR_PRICE', $competitor_price);

            $smarty->assign('NAME', $name);
            $smarty->assign('EMAIL', $email);
            $smarty->assign('PHONE', $phone);

            $smarty->assign('MESSAGE', nl2br($message_body));
            $smarty->caching = false;

            $html_mail = $smarty->fetch(CURRENT_TEMPLATE . $email_template_html);
            $txt_mail  = $smarty->fetch(CURRENT_TEMPLATE . $email_template_txt);
            $txt_mail  = str_replace(array('<br />', '<br/>', '<br>'), '', $txt_mail);
        } else {
            $txt_mail  = sprintf(EMAIL_SENT_BY, parse_multi_language_value(CONTACT_US_NAME, $_SESSION['language_code']), parse_multi_language_value(CONTACT_US_EMAIL_ADDRESS, $_SESSION['language_code'])) . "\n" .
              "--------------------------------------------------------------" . "\n" .
              EMAIL_NAME . $name . "\n" .
              EMAIL_EMAIL . trim($email) . "\n" .
              "\n" . EMAIL_MESSAGE . "\n " . $message_body . "\n";
            $html_mail = nl2br($txt_mail);
        }

        xtc_php_mail(
            CONTACT_US_EMAIL_ADDRESS,
            'HybridSupply',
            CONTACT_US_EMAIL_ADDRESS,
            'HybridSupply',
            CONTACT_US_FORWARDING_STRING,
            trim($email),
            $name,
            '',
            '',
            'Günstiger gesehen',
            $html_mail,
            $txt_mail
        );

        xtc_redirect(xtc_href_link(FILENAME_POPUP_CONTENT, 'action=success&coID=' . $_GET['coID']));
    }
}

/**
 * Success
 */
if ('success' === $action) {
    $smarty->assign('success', true);
} else {
    if (isset($_SESSION['customer_id']) && 'success' !== $action) {
        $customer_query = xtc_db_query(
            'SELECT c.customers_email_address,
                    c.customers_telephone,
                    c.customers_fax,
                    ab.entry_company,
                    ab.entry_street_address,
                    ab.entry_city,
                    ab.entry_postcode
               FROM ' . TABLE_CUSTOMERS . ' c
               JOIN ' . TABLE_ADDRESS_BOOK . ' ab  ON ab.customers_id    = c.customers_id
                                                  AND ab.address_book_id = c.customers_default_address_id
              WHERE c.customers_id = ' . $_SESSION['customer_id']
        );
        $customer_data   = xtc_db_fetch_array($customer_query);
        $customer_data   = array_map('stripslashes', $customer_data);
        $name     = empty($_POST['name'])
                  ? $_SESSION['customer_first_name'] . ' ' . $_SESSION['customer_last_name']
                  : $_POST['name'];
        $email    = empty($_POST['email'])    ? $customer_data['customers_email_address'] : $_POST['email'];
        $phone    = empty($_POST['phone'])    ? $customer_data['customers_telephone']     : $_POST['phone'];
        $fax      = empty($_POST['fax'])      ? $customer_data['customers_fax']           : $_POST['fax'];
        $company  = empty($_POST['company'])  ? $customer_data['entry_company']           : $_POST['company'];
        $street   = empty($_POST['street'])   ? $customer_data['entry_street_address']    : $_POST['street'];
        $postcode = empty($_POST['postcode']) ? $customer_data['entry_postcode']          : $_POST['postcode'];
        $city     = empty($_POST['city'])     ? $customer_data['entry_city']              : $_POST['city'];
    }

    $smarty->assign('CONTACT_CONTENT', $shop_content_data['content_text'] ?? '');
    $smarty->assign('FORM_ACTION', xtc_draw_form('seen-cheaper', xtc_href_link(FILENAME_POPUP_CONTENT, 'action=send&coID=' . $_GET['coID'], 'SSL')) . secure_form());
    if ('true' === DISPLAY_PRIVACY_CHECK) {
        $smarty->assign('PRIVACY_CHECKBOX', xtc_draw_checkbox_field('privacy', 'privacy', $privacy, 'id="privacy"'));
    }
    $smarty->assign('PRIVACY_LINK', str_replace('target="_blank"', '', $main->getContentLink(2, MORE_INFO)));

    $smarty->assign('INPUT_NAME', xtc_draw_input_field('name', ((isset($name)) ? $name : '')));
    $smarty->assign('INPUT_EMAIL', xtc_draw_input_field('email', ((isset($email)) ? $email : '')));
    $smarty->assign('INPUT_PHONE', xtc_draw_input_field('phone', ((isset($phone)) ? $phone : '')));
    $smarty->assign('INPUT_COMPANY', xtc_draw_input_field('company', ((isset($company)) ? $company : '')));
    $smarty->assign('INPUT_STREET', xtc_draw_input_field('street', ((isset($street)) ? $street : '')));
    $smarty->assign('INPUT_POSTCODE', xtc_draw_input_field('postcode', ((isset($postcode)) ? $postcode : '')));
    $smarty->assign('INPUT_CITY', xtc_draw_input_field('city', ((isset($city)) ? $city : '')));
    $smarty->assign('INPUT_FAX', xtc_draw_input_field('fax', ((isset($fax)) ? $fax : '')));
    $smarty->assign('INPUT_TEXT', xtc_draw_textarea_field('message_body', 'soft', 45, 15, ((isset($message_body)) ? $message_body : 'Ich interessiere mich für diesen Artikel, jedoch habe ich ihn zu einem günstigen Preis bei einem Ihrer Mitbewerber gesehen. Bitte teilen Sie mir mit, ob Sie den oben genannten Artikel unterbieten können.')));

    $smarty->assign('INPUT_PRODUCTS_ID', xtc_draw_hidden_field('products_id', ((isset($_REQUEST['products_id'])) ? $_REQUEST['products_id'] : '')));
    $smarty->assign('INPUT_COMPETITOR_URL', xtc_draw_input_field('competitor_url', ((isset($competitor_url)) ? $competitor_url : ''), '', 'url'));
    $smarty->assign('INPUT_COMPETITOR_PRICE', xtc_draw_input_field('competitor_price', ((isset($competitor_price)) ? $competitor_price : ''), 'step="any"', 'number'));

    $smarty->assign('BUTTON_SUBMIT', xtc_image_submit('button_send.gif', IMAGE_BUTTON_SEND));
    $smarty->assign('FORM_END', '</form>');
}

$smarty->assign('language', $_SESSION['language']);
$smarty->caching = false;
$smarty->display(CURRENT_TEMPLATE . '/module/grandeljay_seen_cheaper.html');

$smarty->clear_assign('BUTTON_CONTINUE');
$smarty->clear_assign('CONTENT_HEADING');
$content_body = '';

$disable_smarty_cache = true;
