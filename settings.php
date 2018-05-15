<?php

/**
 * Load the HaTeMiLe settings page.
 */
function loadHatemileSettingsPage()
{
    include plugin_dir_path(__FILE__) . 'page.php';
}

/**
 * Add a link in admin above Settings menu.
 */
function addHatemilePage()
{
    add_options_page(
        'HaTeMiLe for WP',
        'HaTeMiLe for WP',
        'manage_options',
        'hatemile-settings',
        'loadHatemileSettingsPage'
    );
}

/** 
 * Display the fields of HaTeMiLe for WP.
 * @param mixed $args Arguments of add_settings_field function.
 */
function hatemileDisplayField($args)
{
    $field = $args['field'];
    $optionName = 'hatemile_' . $field['id'];
    if (isEnableHaTeMiLeOption($optionName)) {
        $checked = ' checked="checked"';
    } else {
        $checked = '';
    }
    echo (
        '<input type="checkbox" value="on" name="' .
        $optionName .
        '" id="' .
        $optionName .
        '"' .
        $checked .
        ' />'
    );
}

/**
 * Sanitize the value of field as needed.
 * 
 * @param string $input The value of field.
 */
function hatemileSanitize($input)
{
    if ($input !== 'on') {
        $newInput = 'off';
    } else {
        $newInput = $input;
    }
    return $newInput;
}

/**
 * Generate the section of HaTeMiLe for WP.
 * @param string[] $section The section.
 */
function hatemileSection($section) {
}

/**
 * Configure the settings of HaTeMiLe for WP.
 */
function hatemileInit()
{
    global $hatemileOptions;

    add_settings_section(
        'setting_section_id',
        'HaTeMiLe settings',
        'hatemileSection',
        'hatemile-settings'
    );

    foreach ($hatemileOptions as $option) {
        $optionName = 'hatemile_' . $option['id'];
        register_setting(
            'hatemile_option_group',
            $optionName,
            'hatemileSanitize'
        );

        add_settings_field(
            $optionName,
            $option['label'],
            'hatemileDisplayField',
            'hatemile-settings',
            'setting_section_id',
            array('label_for' => $optionName, 'field' => $option)
        );
    }
}

/**
 * Generate HaTeMiLe for WP settings page and configure it.
 */
function generateHaTeMiLeSettings()
{
    add_action('admin_init', 'hatemileInit');
    add_action('admin_menu', 'addHatemilePage');
}
