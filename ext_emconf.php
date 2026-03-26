<?php

/**
 * Local extension configuration
 *
 * @category  Tollwerk
 * @package   Tollwerk\TwViewhelpers
 * @author    tollwerk GmbH <info@tollwerk.de>
 * @copyright 2024 tollwerk GmbH <info@tollwerk.de>
 * @license   GPL https://www.gnu.org/licenses/gpl-3.0.html.en
 * @link      https://tollwerk.de
 */

$EM_CONF[$_EXTKEY] = [
    'title' => 'tollwerk ViewHelpers',
    'description' => 'Collection of useful ViewHelpers',
    'category' => 'plugin',
    'author' => 'tollwerk GmbH',
    'author_email' => 'info@tollwerk.de',
    'author_company' => 'tollwerk GmbH',
    'state' => 'alpha',
    'version' => '0.0.1',
    'constraints' => [
        'depends' => [
            'typo3' => '12.0.0-12.4.99',
        ],
        'conflicts' => [
        ],
        'suggests' => [
        ],
    ],
];
