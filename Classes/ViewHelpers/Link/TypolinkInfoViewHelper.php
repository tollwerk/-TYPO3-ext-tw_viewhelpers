<?php

/**
 * TypolinkInfoViewHelper
 *
 * @category   Tollwerk
 * @package    Tollwerk\TwViewhelpers
 * @subpackage Tollwerk\TwViewhelpers\ViewHelpers
 * @author     tollwerk GmbH <info@tollwerk.de>
 * @copyright  2025 tollwerk Gmbh <info@tollwerk.de>
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 * @link       https://tollwerk.de
 */

namespace Tollwerk\TwViewhelpers\ViewHelpers\Link;

use TYPO3\CMS\Core\LinkHandling\TypoLinkCodecService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Return information about a given Typolink string
 *
 * @category   Tollwerk
 * @package    Tollwerk\TwViewhelpers
 * @subpackage Tollwerk\TwViewhelpers\ViewHelpers
 * @author     tollwerk GmbH <info@tollwerk.de>
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 * @link       https://tollwerk.de
 */
class TypolinkInfoViewHelper extends AbstractViewHelper
{
    /**
     * InitializeArguments
     *
     * @return void
     */
    public function initializeArguments(): void
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
