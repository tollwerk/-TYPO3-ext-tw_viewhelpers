<?php

/**
 * File ext_localconf.php
 *
 * @category  Tollwerk
 * @package   Tollwerk\TwViewhelpers
 * @author    tollwerk GmbH <info@tollwerk.de>
 * @copyright 2024 tollwerk GmbH <info@tollwerk.de>
 * @license   GPL https://www.gnu.org/licenses/gpl-3.0.html.en
 * @link      https://tollwerk.de
 */

if (!defined('TYPO3')) {
    die('Access denied.');
}

call_user_func(
    function () {
        // Register Fluid ViewHelper namespaces.
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['fluid']['namespaces']['twvhs'] = ['Tollwerk\\TwViewhelpers\\ViewHelpers'];
    }
);
