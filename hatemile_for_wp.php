<?php
/*
  Plugin Name: HaTeMiLe for WP
  Description: HaTeMiLe for WP improve accessibility web pages, converting pages with HTML code into pages with a more accessible code.
  Version: 1.0
  Author: Carlson Santana Cruz
  License: Apache License, Version 2.0
  License URI: http://www.apache.org/licenses/LICENSE-2.0
*/

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
}
