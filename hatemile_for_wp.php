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
    ob_start('executeHatemile');
}

/**
 * Execute the HaTeMiLe.
 * @param string $html The HTML code of page.
 * @return string The new HTML code of page.
 */
function executeHatemile($html)
{
    try {
        $hatemilePath = join(DIRECTORY_SEPARATOR, array(
            plugin_dir_path(__FILE__),
            'hatemile_for_php',
            'src',
            'hatemile'
        ));
        require_once join(DIRECTORY_SEPARATOR, array(
            plugin_dir_path(__FILE__),
            'phpQuery',
            'phpQuery',
            'phpQuery.php'
        ));
        require_once join(DIRECTORY_SEPARATOR, array(
            $hatemilePath,
            'implementation',
            'AccessibleAssociationImplementation.php'
        ));
        require_once join(DIRECTORY_SEPARATOR, array(
            $hatemilePath,
            'implementation',
            'AccessibleCSSImplementation.php'
        ));
        require_once join(DIRECTORY_SEPARATOR, array(
            $hatemilePath,
            'implementation',
            'AccessibleDisplayScreenReaderImplementation.php'
        ));
        require_once join(DIRECTORY_SEPARATOR, array(
            $hatemilePath,
            'implementation',
            'AccessibleEventImplementation.php'
        ));
        require_once join(DIRECTORY_SEPARATOR, array(
            $hatemilePath,
            'implementation',
            'AccessibleFormImplementation.php'
        ));
        require_once join(DIRECTORY_SEPARATOR, array(
            $hatemilePath,
            'implementation',
            'AccessibleNavigationImplementation.php'
        ));
        require_once join(DIRECTORY_SEPARATOR, array(
            $hatemilePath,
            'util',
            'Configure.php'
        ));
        require_once join(DIRECTORY_SEPARATOR, array(
            $hatemilePath,
            'util',
            'css',
            'phpcssparser',
            'PHPCSSParser.php'
        ));
        require_once join(DIRECTORY_SEPARATOR, array(
            $hatemilePath,
            'util',
            'html',
            'phpquery',
            'PhpQueryHTMLDOMParser.php'
        ));

        $configure = new hatemile\util\Configure();
        $htmlParser = new hatemile\util\html\phpquery\PhpQueryHTMLDOMParser($html);
        $cssParser = new hatemile\util\css\phpcssparser\PHPCSSParser($htmlParser);

        $accessibleEvent = new hatemile\implementation\AccessibleEventImplementation($htmlParser, $configure);
        $accessibleCSS = new hatemile\implementation\AccessibleCSSImplementation($htmlParser, $cssParser, $configure);
        $accessibleForm = new hatemile\implementation\AccessibleFormImplementation($htmlParser, $configure);
        $accessibleNavigation = new hatemile\implementation\AccessibleNavigationImplementation($htmlParser, $configure);
        $accessibleAssociation = new hatemile\implementation\AccessibleAssociationImplementation($htmlParser, $configure);
        $accessibleDisplay = new hatemile\implementation\AccessibleDisplayScreenReaderImplementation($htmlParser, $configure);

        $accessibleEvent->makeAccessibleAllDragandDropEvents();
        $accessibleEvent->makeAccessibleAllClickEvents();
        $accessibleEvent->makeAccessibleAllHoverEvents();

        $accessibleForm->markAllAutoCompleteFields();
        $accessibleForm->markAllRequiredFields();
        $accessibleForm->markAllRangeFields();
        $accessibleForm->markAllInvalidFields();

        $accessibleNavigation->provideNavigationByAllHeadings();
        $accessibleNavigation->provideNavigationByAllSkippers();
        $accessibleNavigation->provideNavigationToAllLongDescriptions();

        $accessibleAssociation->associateAllDataCellsWithHeaderCells();
        $accessibleAssociation->associateAllLabelsWithFields();

        $accessibleDisplay->displayAllShortcuts();
        $accessibleDisplay->displayAllRoles();
        $accessibleDisplay->displayAllCellHeaders();
        $accessibleDisplay->displayAllWAIARIAStates();
        $accessibleDisplay->displayAllLinksAttributes();
        $accessibleDisplay->displayAllTitles();
        $accessibleDisplay->displayAllLanguages();
        $accessibleDisplay->displayAllAlternativeTextImages();

        $accessibleNavigation->provideNavigationByAllSkippers();
        $accessibleDisplay->displayAllShortcuts();

        $accessibleCSS->provideAllSpeakProperties();

        return $htmlParser->getHTML();
    } catch (Exception $exception) {
        return $html;
    }
    return $html;
}

if (!is_admin()) {
    add_action('init', 'executeHatemileByBuffer');
    add_filter('shutdown', 'ob_end_flush');
}
