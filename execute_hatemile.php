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
 * Load the static files using URLs, to reduce the size of pages.
 * @param \hatemile\util\html\HTMLDOMParser $htmlParser The HTML parser.
 */
function loadStaticFilesFromHatemile($htmlParser) {
    $local = $htmlParser->find('head')->firstResult();
    $body = $htmlParser->find('body')->firstResult();
    if (($local !== null) && ($body !== null)) {
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


        $javascriptPath = (
            '/wp-content/plugins/hatemile-for-wp/hatemile_for_php/src/js/'
        );

        $commonFunctionsScript = $htmlParser->createElement('script');
        $commonFunctionsScript->setAttribute(
            'id',
            AccessibleEventImplementation::ID_SCRIPT_COMMON_FUNCTIONS
        );
        $commonFunctionsScript->setAttribute('type', 'text/javascript');
        $commonFunctionsScript->setAttribute(
            'src',
            get_site_url(
                get_current_blog_id(),
                $javascriptPath . 'common.js'
            )
        );
        $local->prependElement($commonFunctionsScript);

        $scriptEventListener = $htmlParser->createElement('script');
        $scriptEventListener->setAttribute(
            'id',
            AccessibleEventImplementation::ID_SCRIPT_EVENT_LISTENER
        );
        $scriptEventListener->setAttribute('type', 'text/javascript');
        $scriptEventListener->setAttribute(
            'src',
            get_site_url(
                get_current_blog_id(),
                $javascriptPath . 'eventlistener.js'
            )
        );
        $commonFunctionsScript->insertAfter($scriptEventListener);

        $scriptList = $htmlParser->createElement('script');
        $scriptList->setAttribute(
            'id',
            AccessibleEventImplementation::ID_LIST_IDS_SCRIPT
        );
        $scriptList->setAttribute('type', 'text/javascript');
        $scriptList->appendText('var activeElements = [];');
        $scriptList->appendText('var hoverElements = [];');
        $scriptList->appendText('var dragElements = [];');
        $scriptList->appendText('var dropElements = [];');
        $body->appendElement($scriptList);

        $scriptFunction = $htmlParser->createElement('script');
        $scriptFunction->setAttribute(
            'id',
            AccessibleEventImplementation::ID_FUNCTION_SCRIPT_FIX
        );
        $scriptFunction->setAttribute('type', 'text/javascript');
        $scriptFunction->setAttribute(
            'src',
            get_site_url(
                get_current_blog_id(),
                $javascriptPath . 'include.js'
            )
        );
        $body->appendElement($scriptFunction);

        $scriptValidate = $htmlParser->createElement('script');
        $scriptValidate->setAttribute(
            'id',
            AccessibleFormImplementation::ID_SCRIPT_EXECUTE_VALIDATION
        );
        $scriptValidate->setAttribute('type', 'text/javascript');
        $scriptValidate->setAttribute(
            'src',
            get_site_url(
                get_current_blog_id(),
                $javascriptPath . 'validation.js'
            )
        );
        $body->appendElement($scriptValidate);
    }
}

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

        loadStaticFilesFromHatemile($htmlParser);

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

        return $htmlParser->getHTML();
    } catch (Exception $exception) {
        return $html;
    }
}
