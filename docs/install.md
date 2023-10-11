# Seen Cheaper?

## Installation

### Step 1

Edit `/tpl_modified_responsive_6/module/product_info/product_info_tabs_vs.html` (or whichever product page template you are using) and add the following to it:

```smarty
{config_load file="$language/extra/grandeljay_seen_cheaper.conf"}

{if $GRANDELJAY_SEEN_CHEAPER}<div class="grandeljay_seen_cheaper" style="text-align: right;"><a class="iframe" href="{$GRANDELJAY_SEEN_CHEAPER}" style="color: var(--colorPrimary);">{#text_seen_cheaper#}</a></div>{/if}
```

### Step 2

Edit `/popup_content.php` and add the following:

```php
/**
 * Seen Cheaper
 *
 * @author  Jay Trees <modified-seen-cheaper@grandels.email>
 * @link    https://github.com/grandeljay/modified-seen-cheaper
 * @package GrandeljaySeenCheaper
 */
require_once DIR_FS_INC . 'xtc_validate_email.inc.php';
/** */

/**
 * Seen Cheaper
 *
 * @author  Jay Trees <modified-seen-cheaper@grandels.email>
 * @link    https://github.com/grandeljay/modified-seen-cheaper
 * @package GrandeljaySeenCheaper
 */
$popup_smarty->assign('html_params', ((TEMPLATE_HTML_ENGINE == 'xhtml') ? ' '.HTML_PARAMS : ' lang="'.$_SESSION['language_code'].'" style="overflow-x: hidden;"'));
/** */
```

### Step 3

Create a new content in the content manager and make sure it has the same coID (currently `22`) as the link in `/includes/extra/modules/product_info_end/grandeljay_seen_cheaper.php`.
