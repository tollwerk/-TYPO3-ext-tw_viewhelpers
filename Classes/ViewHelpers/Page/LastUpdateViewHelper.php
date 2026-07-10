<?php

/**
 * HeadingViewHelper
 *
 * @category   Tollwerk
 * @package    Tollwerk\TwViewhelpers
 * @subpackage Tollwerk\TwViewhelpers\ViewHelpers
 * @author     tollwerk GmbH <info@tollwerk.de>
 * @copyright  2025 tollwerk Gmbh <info@tollwerk.de>
 * @license    GPL https://www.gnu.org/licenses/gpl-3.0.html.en
 * @link       https://tollwerk.de
 */

namespace Tollwerk\TwViewhelpers\ViewHelpers\Page;

use Psr\Http\Message\ServerRequestInterface;
use Tollwerk\TwViewhelpers\Service\PageService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Get information about the last changes to the current or a given page.
 * Author information is retrieved via PageService.php which checks page properties `author` and `author_email`
 * from the current page up the root line to the root page.
 *
 * @category   Tollwerk
 * @package    Tollwerk\TwViewhelpers
 * @subpackage Tollwerk\TwViewhelpers\ViewHelpers
 * @author     tollwerk GmbH <info@tollwerk.de>
 * @license    GPL https://www.gnu.org/licenses/gpl-3.0.html.en
 * @link       https://tollwerk.de
 */
class LastUpdateViewHelper extends AbstractViewHelper
{
    /**
     * Get Request
     *
     * @return ServerRequestInterface|null
     */
    private function getRequest(): ServerRequestInterface|null
    {
        if ($this->renderingContext->hasAttribute(ServerRequestInterface::class)) {
            return $this->renderingContext->getAttribute(ServerRequestInterface::class);
        }
        return null;
    }


    /**
     * InitializeArguments
     *
     * @return void
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('page', 'int', 'UID of the desired page', false, null);
    }



    /**
     * RenderStatic
     *
     * @param array                     $arguments             Arguments
     * @param \Closure                  $renderChildrenClosure Closure
     * @param RenderingContextInterface $renderingContext      RenderingContext
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * @return array
     */
    public function render(): array
    {
        // Get UID of current page.
        $pageUid = $this->arguments['page'];
        if ($pageUid === null) {
            $pageUid = $this->renderingContext->getRequest()->getAttribute('routing')->getPageId();
        }
        return GeneralUtility::makeInstance(PageService::class)->getLastUpdate($pageUid);
    }
}
