<?php
/**
 * render meta fields of wc product attributes test page
 */

use MedLight\Utils\TranslationUtils as TRNS;

$current_lang = TRNS::get_lang($term->term_id, 'term');  // get curren tterm lang

// assign trid or create a new one
$trid = TRNS::get_trid($term->term_id, 'term'); 
TRNS::set_trid( $term->term_id, 'term', $trid );

$languages = TRNS::get_all_languages();
?>

<tr class="form-field">
    <th colspan="2"><h2><?php _e('Translations', 'medlight'); ?></h2></th>
</tr>
<?php

// render dropdowns
foreach ($languages as $language):
    if ($language->slug === $current_lang) continue;   // for each language other than current

    $terms = get_terms([
        'taxonomy'   => $taxonomy,
        'hide_empty' => false,
        'lang'       => $language->slug,
    ]);

    $existing_translation = TRNS::get_translation_id($term->term_id, 'term', $language->slug);
    ?>

    <tr class="form-field">
        <th scope="row">
            <label for="pll_term_<?php echo $language->slug; ?>"><?php esc_html_e($language->name) ?></label>
        </th>
        <td>
            <select name="pll_term[ <?php echo $language->slug; ?> ]" id="pll_term_<?php echo $language->slug; ?>">
                <option value=""><?php _e("— None —"); ?></option>
                <?php foreach ($terms as $t):   // foreach available terms to link/relate ?>
                    <option value="<?php echo $t->term_id; ?>'" 
                        <?php selected($t->term_id, $existing_translation, true) ?> 
                    >
                        <?php echo esc_html_e($t->name) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </td>
    </tr>
    
<?php endforeach; ?>