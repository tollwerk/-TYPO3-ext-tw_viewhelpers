<?php

/**
 * Heading Context Utility
 *
 * @category   Tollwerk
 * @package    Tollwerk\TwViewhelpers
 * @subpackage Tollwerk\TwViewhelpers\Heading
 * @author     tollwerk GmbH <info@tollwerk.de>
 * @copyright  2024 tollwerk Gmbh <info@tollwerk.de>
 * @license    GPL https://www.gnu.org/licenses/gpl-3.0.html.en
 * @link       https://tollwerk.de
 */

namespace Tollwerk\TwViewhelpers\Heading;

/**
 * Heading context
 *
 * @category   Tollwerk
 * @package    Tollwerk\TwViewhelpers
 * @subpackage Tollwerk\TwViewhelpers\Heading
 * @author     tollwerk GmbH <info@tollwerk.de>
 * @copyright  2024 tollwerk Gmbh <info@tollwerk.de>
 * @license    GPL https://www.gnu.org/licenses/gpl-3.0.html.en
 * @link       https://tollwerk.de
 */
class HeadingContext
{
    /**
     * Heading level
     *
     * @var int
     */
    protected $level;
    /**
     * Visual headline type
     *
     * @var string
     */
    protected $visualType;
    /**
     * Follow-up headline level
     *
     * @var int
     */
    protected $afterLevel;
    /**
     * Heading is hidden
     *
     * @var boolean
     */
    protected $hidden;
    /**
     * Semantic error
     *
     * @var boolean
     */
    protected $error;

    /**
     * Constructor
     *
     * @param int    $level      Heading level
     * @param string $visualType Visual headline type
     * @param int    $afterLevel Previous headline level
     * @param bool   $hidden     Heading is hidden
     * @param bool   $error      Heading violates semantic structure
     *
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     * @codingStandardsIgnoreStart
     */
    public function __construct($level, $visualType, $afterLevel, $hidden = false, $error = false)
    {
        $this->level      = $level;
        $this->visualType = $visualType;
        $this->afterLevel = $afterLevel;
        $this->hidden     = (bool)$hidden;
        $this->error      = (bool)$error;
    }

    /**
     * Return the headline level
     *
     * @return int Heading level
     */
    public function getLevel(): int
    {
        return $this->level;
    }

    /**
     * Return the visual headline type
     *
     * @return string Visual headline type
     */
    public function getVisualType(): string
    {
        return $this->visualType;
    }

    /**
     * Return the follow-up headline level
     *
     * @return int Follow-up headline level
     */
    public function getAfterLevel(): int
    {
        return $this->afterLevel;
    }

    /**
     * Return whether the headline is hidden
     *
     * @return bool Heading is hidden
     */
    public function isHidden(): bool
    {
        return $this->hidden;
    }

    /**
     * Return whether the headline violates the semantic structure
     *
     * @return bool Heading error
     */
    public function isError(): bool
    {
        return $this->error;
    }
}
