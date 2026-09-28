<?php

/**
 * InlineSvgViewHelper
 *
 * @category   Tollwerk
 * @package    Tollwerk\TwViewhelpers
 * @subpackage Tollwerk\TwViewhelpers\ViewHelpers\Image
 * @author     tollwerk GmbH <info@tollwerk.de>
 * @copyright  2026 tollwerk Gmbh <info@tollwerk.de>
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 * @link       https://tollwerk.de
 */

namespace Tollwerk\TwViewhelpers\ViewHelpers\Image;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Core\Resource\FileReference as CoreFileReference;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Render SVG file object as inline SVG code
 *
 * @category   Tollwerk
 * @package    Tollwerk\TwViewhelpers
 * @subpackage Tollwerk\TwViewhelpers\ViewHelpers\Image
 * @author     tollwerk GmbH <info@tollwerk.de>
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 * @link       https://tollwerk.de
 */
class InlineSvgViewHelper extends AbstractViewHelper
{
    /**
     * Don't escape HTML output
     *
     * @var bool
     */
    protected $escapeOutput = false;

    /**
     * Initialize all arguments. You need to override this method and call
     * $this->registerArgument(...) inside this method, to register all your arguments.
     *
     * @api
     *
     * @return void
     */
    public function initializeArguments(): void
    {
        parent::initializeArguments();
        $this->registerArgument('file', 'mixed', 'SVG file as FileReference, CoreFileRefer or file path as string', false, null);
    }

    /**
     * Return xml content, stripping the outer <xml> tag.
     *
     * @param string $fileContent File content
     *
     * @return array|string|string[]|null
     */
    public static function returnXmlContent(string $fileContent = '')
    {
        return preg_replace('/<\?xml(.*)>/', '', $fileContent);
    }

    /**
     * Default implementation of static rendering; useful API method if your ViewHelper
     * when compiled is able to render itself statically to increase performance. This
     * default implementation will simply delegate to the ViewHelperInvoker.
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * @return string
     */
    public function render(): string
    {
        // Get file or return null if not set.
        if ($this->arguments['file'] === null) {
            return '';
        }
        $file = $this->arguments['file'];

        // Return file content of Extbase File Reference.
        if ($file instanceof FileReference) {
            return self::returnXmlContent($file->getOriginalResource()->getContents());
        }

        // Return file content of TYPO3 Core File Reference.
        if ($file instanceof CoreFileReference) {
            return self::returnXmlContent($file->getContents());
        }

        // If file is a string, try to resolve the path and get the file contents directly.
        if (is_string($file)) {
            $absFilePath = GeneralUtility::getFileAbsFileName($file);
            if ($absFilePath !== '' && is_file($absFilePath)) {
                return file_get_contents($absFilePath);
            }
        }

        // Return comment when file is not supported.
        return "<!-- InlineSvgViewHelper: Could not render SVG for $file -->";
    }
}
