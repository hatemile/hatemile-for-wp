<?php
/*
  Plugin Name: HaTeMiLe for WP
  Description: HaTeMiLe for WP improve accessibility web pages, converting pages with HTML code into pages with a more accessible code.
  Version: 1.0
  Author: Carlson Santana Cruz
  License: Apache License, Version 2.0
  License URI: http://www.apache.org/licenses/LICENSE-2.0
*/

$hatemileOptions = array(
    array(
        'id' => 'hide_hatemile_changes',
        'label' => 'Hide visual changes of HaTeMiLe',
        'default' => 'on'
    ),
    array(
        'id' => 'display_all_alternative_text_images',
        'label' => 'Display the alternative text of all images',
        'default' => 'on'
    ),
    array(
        'id' => 'display_all_cell_headers',
        'label' => 'Display the headers of each data cell of all tables',
        'default' => 'on'
    ),
    array(
        'id' => 'display_all_languages',
        'label' => 'Display the language of all elements',
        'default' => 'on'
    ),
    array(
        'id' => 'display_all_links-attributes',
        'label' => 'Display the attributes of all links',
        'default' => 'on'
    ),
    array(
        'id' => 'display_all_roles',
        'label' => 'Display the WAI-ARIA roles of all elements',
        'default' => 'on'
    ),
    array(
        'id' => 'display_all_titles',
        'label' => 'Display the titles of all elements',
        'default' => 'on'
    ),
    array(
        'id' => 'display_all_shortcuts',
        'label' => 'Display all shortcuts',
        'default' => 'on'
    ),
    array(
        'id' => 'display_all_wai_aria_states',
        'label' => 'Display the WAI-ARIA attributes of all elements',
        'default' => 'on'
    ),
    array(
        'id' => 'provide_navigation_to_all_long_descriptions',
        'label' => 'Provide links to access the longs descriptions',
        'default' => 'on'
    ),
    array(
        'id' => 'provide_navigation_by_all_headings',
        'label' => 'Provide navigation by headings',
        'default' => 'on'
    ),
    array(
        'id' => 'provide_navigation_by_all_skippers',
        'label' => 'Provide navigation by content skippers',
        'default' => 'on'
    )
);

/**
 * Returns the value of option.
 * @param string $optionName The option name.
 * @return boolean True if the option is enabled or false if the options is
 * disabled.
 */
function isEnableHaTeMiLeOption($optionName) {
    global $hatemileOptions;
    $optionValue = get_option($optionName);
    if ((!empty($optionValue)) && ($optionValue === 'on')) {
        return true;
    } else {
        $option = null;
        foreach ($hatemileOptions as $hatemileOption) {
            if (('hatemile_' . $hatemileOption['id']) === $optionName) {
                $option = $hatemileOption;
                break;
            }
        }
        if (
            (empty($optionValue))
            && ($option !== null)
            && ($option['default'] === 'on')
        ) {
            return true;
        } else {
            return false;
        }
    }
}

/**
 * Execute the HaTeMiLe by buffer of PHP.
 */
function executeHatemileByBuffer()
{
    require_once join(DIRECTORY_SEPARATOR, array(
        plugin_dir_path(__FILE__),
        'execute_hatemile.php'
    ));
    ob_start('executeHatemile');
}

if (!is_admin()) {
    add_action('init', 'executeHatemileByBuffer');
} else {
    require_once join(DIRECTORY_SEPARATOR, array(
        plugin_dir_path(__FILE__),
        'settings.php'
    ));
    generateHaTeMiLeSettings();
}
