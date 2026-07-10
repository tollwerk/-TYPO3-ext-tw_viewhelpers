<?php

/**
 * TypolinkInfoViewHelper
 *
 * @category   Tollwerk
 * @package    Tollwerk\TwViewhelpers
 * @subpackage Tollwerk\TwViewhelpers\ViewHelpers
 * @author     tollwerk GmbH <info@tollwerk.de>
 * @copyright  2025 tollwerk Gmbh <info@tollwerk.de>
 * @license    GPL https://www.gnu.org/licenses/gpl-3.0.html.en
 * @link       https://tollwerk.de
 */

namespace Tollwerk\TwViewhelpers\ViewHelpers\Link;

use TYPO3\CMS\Core\LinkHandling\TypoLinkCodecService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Return information about a given Typolink string
 *
 * @category   Tollwerk
 * @package    Tollwerk\TwViewhelpers
 * @subpackage Tollwerk\TwViewhelpers\ViewHelpers
 * @author     tollwerk GmbH <info@tollwerk.de>
 * @license    GPL https://www.gnu.org/licenses/gpl-3.0.html.en
 * @link       https://tollwerk.de
 */
class TypolinkInfoViewHelper extends AbstractViewHelper
{
    /**
     * InitializeArguments
     *
     * @return void
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('typolink', 'string', 'The typolink', true);
    }

    /**
     * RenderStatic
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * @return array
     */
    public function render(): array
    {
        return GeneralUtility::makeInstance(
            TypoLinkCodecService::class
        )->decode($this->arguments['typolink']);
    }
}
