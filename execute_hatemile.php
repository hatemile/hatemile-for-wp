<?php

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

use hatemile\implementation\AccessibleAssociationImplementation;
use hatemile\implementation\AccessibleDisplayScreenReaderImplementation;
use hatemile\implementation\AccessibleEventImplementation;
use hatemile\implementation\AccessibleFormImplementation;
use hatemile\implementation\AccessibleNavigationImplementation;
use hatemile\util\Configure;
use hatemile\util\html\phpquery\PhpQueryHTMLDOMParser;

/**
 * Execute the HaTeMiLe.
 * @param string $html The HTML code of page.
 * @return string The new HTML code of page.
 */
function executeHatemile($html)
{
    try {
        $configure = new Configure();
        $htmlParser = new PhpQueryHTMLDOMParser($html);

        $accessibleEvent = new AccessibleEventImplementation(
            $htmlParser,
            $configure
        );
        $accessibleForm = new AccessibleFormImplementation(
            $htmlParser,
            $configure
        );
        $accessibleNavigation = new AccessibleNavigationImplementation(
            $htmlParser,
            $configure
        );
        $accessibleAssociation = new AccessibleAssociationImplementation(
            $htmlParser,
            $configure
        );
        $accessibleDisplay = new AccessibleDisplayScreenReaderImplementation(
            $htmlParser,
            $configure
        );

        $accessibleAssociation->associateAllDataCellsWithHeaderCells();
        $accessibleAssociation->associateAllLabelsWithFields();

        $accessibleEvent->makeAccessibleAllDragandDropEvents();
        $accessibleEvent->makeAccessibleAllClickEvents();
        $accessibleEvent->makeAccessibleAllHoverEvents();

        $accessibleForm->markAllAutoCompleteFields();
        $accessibleForm->markAllRequiredFields();
        $accessibleForm->markAllRangeFields();
        $accessibleForm->markAllInvalidFields();

        $accessibleDisplay->displayAllShortcuts();
        $accessibleDisplay->displayAllRoles();
        $accessibleDisplay->displayAllCellHeaders();
        $accessibleDisplay->displayAllWAIARIAStates();
        $accessibleDisplay->displayAllLinksAttributes();
        $accessibleDisplay->displayAllTitles();
        $accessibleDisplay->displayAllLanguages();
        $accessibleDisplay->displayAllAlternativeTextImages();

        $accessibleNavigation->provideNavigationByAllHeadings();
        $accessibleNavigation->provideNavigationByAllSkippers();
        $accessibleNavigation->provideNavigationToAllLongDescriptions();

        $accessibleNavigation->provideNavigationByAllSkippers();
        $accessibleDisplay->displayAllShortcuts();

        $local = $htmlParser->find('head')->firstResult();
        if ($local !== null) {
            $styleHideElements = $htmlParser->createElement('link');
            $styleHideElements->setAttribute('rel', 'stylesheet');
            $styleHideElements->setAttribute('type', 'text/css');
            $styleHideElements->setAttribute(
                'href',
                get_site_url(
                    get_current_blog_id(),
                    '/wp-content/plugins/hatemile-for-wp/css/hide_changes.css'
                )
            );
            $local->appendElement($styleHideElements);
        }

        return $htmlParser->getHTML();
    } catch (Exception $exception) {
        return $html;
    }
}
