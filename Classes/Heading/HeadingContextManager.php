<?php

/**
 * Heading Context Manager
 *
 * @category   Tollwerk
 * @package    Tollwerk\TwViewhelpers
 * @subpackage Tollwerk\TwViewhelpers\Heading
 * @author     tollwerk GmbH <info@tollwerk.de>
 * @copyright  2026 tollwerk Gmbh <info@tollwerk.de>
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 * @link       https://tollwerk.de
 */

namespace Tollwerk\TwViewhelpers\Heading;

use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Log\Logger;
use TYPO3\CMS\Core\Log\LogManager;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Heading context manager
 *
 * @category   Tollwerk
 * @package    Tollwerk\TwViewhelpers
 * @subpackage Tollwerk\TwViewhelpers\Heading
 * @author     tollwerk GmbH <info@tollwerk.de>
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 * @link       https://tollwerk.de
 */
class HeadingContextManager implements SingletonInterface
{
    /**
     * Visual headline types
     */
    const VISUAL_TYPE_H1 = 'h1';
    const VISUAL_TYPE_H2 = 'h2';
    const VISUAL_TYPE_H3 = 'h3';
    const VISUAL_TYPE_H4 = 'h4';
    const VISUAL_TYPE_H5 = 'h5';
    const VISUAL_TYPE_H6 = 'h6';
    const VISUAL_TYPE_H7 = 'h7';
    const VISUAL_TYPES = [
        1 => self::VISUAL_TYPE_H1,
        2 => self::VISUAL_TYPE_H2,
        3 => self::VISUAL_TYPE_H3,
        4 => self::VISUAL_TYPE_H4,
        5 => self::VISUAL_TYPE_H5,
        6 => self::VISUAL_TYPE_H6,
        7 => self::VISUAL_TYPE_H7,
    ];
    /**
     * Current headline level
     *
     * @var int
     */
    protected int $currentLevel = 0;
    /**
     * Current headline type
     *
     * @var int
     */
    protected int $currentType = 0;
    /**
     * Maximum rendered level
     *
     * @var int
     */
    protected int $maxLevel = 0;
    /**
     * Heading contexts
     *
     * @var HeadingContext[]
     */
    protected array $contexts = [];

    /**
     * ExtensionConfiguration
     *
     * @var array
     */
    protected array $extensionConfiguration;

    /**
     * Constructor
     *
     * @param ExtensionConfiguration $extensionConfiguration
     */
    public function __construct()
    {
        $this->extensionConfiguration = GeneralUtility::makeInstance(
            ExtensionConfiguration::class
        )->get('tw_viewhelpers');
    }

    /**
     * Set up a new headline context
     *
     * @param int|null $level      Desired headline level
     * @param int|null $visualType Visual headline type
     * @param string   $content    Heading content (for logging purposes only)
     *
     * @return HeadingContext Heading context
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @codingStandardsIgnoreStart
     */
    public function setupContext(?int $level = null, ?int $visualType = null, string $content = ''): HeadingContext
    {
        $level      = intval($level);
        $afterLevel = max(1, $this->currentLevel);
        $hidden     = ($level >= 100);
        $level      = ($level >= 100) ? 0 : $level;
        $error      = null;

        if ($level > 0) {
            // If headline levels are skipped: Warning
            if (($level - $this->currentLevel) > 1) {
                $error = true;
                if (!empty($GLOBALS['TYPO3_REQUEST'])) {
                    if ((int) $this->extensionConfiguration['enable_log']) {
                        /**
                         * Logger
                         *
                         * @var Logger $logger
                         */
                        $logger = GeneralUtility::makeInstance(LogManager::class)->getLogger(__CLASS__);
                        $logger->warning(
                            sprintf(
                                'Page %s: skipping headline level(s) %s',
                                $GLOBALS['TYPO3_REQUEST']->getAttribute('frontend.page.information')->getId(),
                                implode(', ', range(max(1, $this->currentLevel) + 1, $level - 1))
                            )
                        );
                    }
                }
            }

            $afterLevel = min($afterLevel, $level);
        }

        if ($level == 0) {
            // Else if the headline level should be determined automatically
            $level = $this->currentLevel + 1;
        }

        // Determine the visual headline type
        $this->currentType = max(1, intval($visualType) ?: $level);
        if ($this->currentType <= count(self::VISUAL_TYPES)) {
            $visualType = self::VISUAL_TYPES[$this->currentType];
        }

        $this->currentLevel = $level;
        $headingContext     = GeneralUtility::makeInstance(
            HeadingContext::class,
            $level,
            $visualType,
            $afterLevel,
            $hidden,
            $error
        );
        $this->contexts[]   = $headingContext;

        return $headingContext;
    }

    /**
     * Tear down the last headline context
     *
     * @param HeadingContext $headingContext Heading context to tear down
     */
    public function tearDownContext(HeadingContext $headingContext): void
    {
        $this->currentLevel = $headingContext->getAfterLevel();
//        debug($this->currentLevel, 'Tear down');
    }

    /**
     * Return a hash representing the current heading context
     *
     * @return string Heading context hash
     */
    public function getCurrentContext(): string
    {
        return count($this->contexts) ? spl_object_hash($this->contexts[count($this->contexts) - 1]) : '';
    }

    /**
     * Return the current heading level
     *
     * @return int Current heading level
     */
    public function getCurrentLevel(): int
    {
        return $this->currentLevel;
    }

    /**
     * Return the current heading type
     *
     * @return int Current heading type
     */
    public function getCurrentType(): int
    {
        return $this->currentType;
    }

    /**
     * Pre-calculate and return the next heading level
     *
     * @param int|null $level Explicit level
     *
     * @return int Next level
     */
    public function getNextLevel(?int $level = null): int
    {
        $level = intval($level);
        $level = ($level >= 100) ? 0 : $level;

        return $level ?: ($this->currentLevel + 1);
    }

    /**
     * Restore a heading context
     *
     * @param string $restoreContext Heading context descriptor
     * @param bool   $restoreRoot    Restore the root level if requested
     *
     * @return string|null Current heading context
     *
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     * @SuppressWarnings(PHPMD.CountInLoopExpression)
     * @codingStandardsIgnoreStart
     */
    public function restoreContext(string $restoreContext, bool $restoreRoot = false): ?string
    {
        $restoreContext = trim($restoreContext);
        $restoreRoot    = $restoreRoot || !$this->currentLevel;
        if (!strlen($restoreContext)) {
            $this->currentLevel = $restoreRoot ? 0 : 1;
            $this->contexts     = [];

            return null;
        }

        $countContext = count($this->contexts);

        while ($countContext && (spl_object_hash($this->contexts[$countContext - 1]) != $restoreContext)) {
            $this->currentLevel = array_pop($this->contexts)->getAfterLevel();
            $countContext       = count($this->contexts);
        }

        return $this->currentLevel;
    }
}
