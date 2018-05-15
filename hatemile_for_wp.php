<?php
/*
  Plugin Name: HaTeMiLe for WP
  Description: HaTeMiLe for WP is a wordpress plugin that convert the HTML code of pages in a code more accessible.
  Version: 1.0
  Author: Carlson Santana Cruz
  License: Apache License, Version 2.0
  License URI: http://www.apache.org/licenses/LICENSE-2.0
*/

const HATEMILE_PLUGIN_DOMAIN = 'hatemile-plugin-domain';

$hatemileOptions = array(
    array(
        'id' => 'hide_hatemile_changes',
        'label' => __(
            'Hide visual changes of HaTeMiLe',
            HATEMILE_PLUGIN_DOMAIN
        ),
        'default' => 'on'
    ),
    array(
        'id' => 'display_all_alternative_text_images',
        'label' => __(
            'Display the alternative text of all images',
            HATEMILE_PLUGIN_DOMAIN
        ),
        'default' => 'on'
    ),
    array(
        'id' => 'display_all_cell_headers',
        'label' => __(
            'Display the headers of each data cell of all tables',
            HATEMILE_PLUGIN_DOMAIN
        ),
        'default' => 'on'
    ),
    array(
        'id' => 'display_all_languages',
        'label' => __(
            'Display the language of all elements',
            HATEMILE_PLUGIN_DOMAIN
        ),
        'default' => 'on'
    ),
    array(
        'id' => 'display_all_links_attributes',
        'label' => __(
            'Display the attributes of all links',
            HATEMILE_PLUGIN_DOMAIN
        ),
        'default' => 'on'
    ),
    array(
        'id' => 'display_all_roles',
        'label' => __(
            'Display the WAI-ARIA roles of all elements',
            HATEMILE_PLUGIN_DOMAIN
        ),
        'default' => 'on'
    ),
    array(
        'id' => 'display_all_titles',
        'label' => __(
            'Display the titles of all elements',
            HATEMILE_PLUGIN_DOMAIN
        ),
        'default' => 'on'
    ),
    array(
        'id' => 'display_all_shortcuts',
        'label' => __('Display all shortcuts', HATEMILE_PLUGIN_DOMAIN),
        'default' => 'on'
    ),
    array(
        'id' => 'display_all_wai_aria_states',
        'label' => __(
            'Display the WAI-ARIA attributes of all elements',
            HATEMILE_PLUGIN_DOMAIN
        ),
        'default' => 'on'
    ),
    array(
        'id' => 'provide_navigation_to_all_long_descriptions',
        'label' => __(
            'Provide links to access the longs descriptions',
            HATEMILE_PLUGIN_DOMAIN
        ),
        'default' => 'on'
    ),
    array(
        'id' => 'provide_navigation_by_all_headings',
        'label' => __('Provide navigation by headings', HATEMILE_PLUGIN_DOMAIN),
        'default' => 'on'
    ),
    array(
        'id' => 'provide_navigation_by_all_skippers',
        'label' => __(
            'Provide navigation by content skippers',
            HATEMILE_PLUGIN_DOMAIN
        ),
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

    $option = null;
    foreach ($hatemileOptions as $hatemileOption) {
        if (('hatemile_' . $hatemileOption['id']) === $optionName) {
            $option = $hatemileOption;
            break;
        }
    }

    $optionValue = get_option($optionName, $option['default']);
    if ((!empty($optionValue)) && ($optionValue === 'on')) {
        return true;
    } else {
        return false;
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
