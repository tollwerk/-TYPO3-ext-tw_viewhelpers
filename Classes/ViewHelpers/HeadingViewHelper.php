<?php

/**
 * HeadingViewHelper
 *
 * @category   Tollwerk
 * @package    Tollwerk\TwViewhelpers
 * @subpackage Tollwerk\TwViewhelpers\ViewHelpers
 * @author     tollwerk GmbH <info@tollwerk.de>
 * @copyright  2026 tollwerk Gmbh <info@tollwerk.de>
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 * @link       https://tollwerk.de
 */

namespace Tollwerk\TwViewhelpers\ViewHelpers;

use Tollwerk\TwViewhelpers\Heading\HeadingContextManager;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper;

/**
 * Render a headline tag with dynamic level (h1, h2 ..)
 *
 * @category   Tollwerk
 * @package    Tollwerk\TwViewhelpers
 * @subpackage Tollwerk\TwViewhelpers\ViewHelpers
 * @author     tollwerk GmbH <info@tollwerk.de>
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 * @link       https://tollwerk.de
 */
class HeadingViewHelper extends AbstractTagBasedViewHelper
{
    /**
     * Escape the output
     *
     * @var bool
     */
    protected $escapeOutput = false;

    /**
     * Arguments initialization
     *
     * @return void
     */
    public function initializeArguments(): void
    {
        parent::initializeArguments();
        $this->registerArgument('level', 'int', 'Heading level', false, null);
        $this->registerArgument('type', 'string', 'Visual type', false, null);
        $this->registerArgument('content', 'string', 'Heading content', true);
        $this->registerArgument('restoreContext', 'boolean', 'Restore the heading context', false, true);
        $this->registerArgument('class', 'string', 'CSS Class', false, '');
    }

    /**
     * Render the heading
     *
     * @return string Heading
     */
    public function render(): string
    {
        $level = $this->arguments['level'];
        $type    = $this->arguments['type'];
        $content = trim($this->arguments['content']);

        /**
         * Heading Context Manager
         *
         * @var HeadingContextManager $headingContextManager
        */
        $headingContextManager = GeneralUtility::makeInstance(HeadingContextManager::class);

        // Set up a headline context
        $headingContext = $headingContextManager->setupContext($level, $type);
        $class = implode(
            ' ',
            array_filter(
                [
                'Heading Heading--' . $headingContext->getVisualType(),
                $headingContext->isError() ? 'Heading--semantic-error' : '',
                $headingContext->isHidden() ? 'Heading--hidden' : '',
                    !empty($this->arguments['class']) ? trim($this->arguments['class']) : ''
                ]
            )
        );

        $headingLevel = $headingContext->getLevel();
        $this->tag->setTagName(($headingLevel > 6) ? 'div' : 'h' . $headingLevel);
        $this->tag->addAttribute('class', $class);
        $this->tag->setContent($content);
        $content = parent::render() . $this->renderChildren();

        // Tear down the headline context
        if ((bool)$this->arguments['restoreContext']) {
            $headingContextManager->tearDownContext($headingContext);
        }

        // Return the Rendered result
        return $content;
    }
}
