<?php

/**
 * PageService
 *
 * @category   Tollwerk
 * @package    Tollwerk\TwViewhelpers
 * @subpackage Tollwerk\TwViewhelpers\Service
 * @author     tollwerk GmbH <info@tollwerk.de>
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 * @link       https://tollwerk.de
 */

namespace Tollwerk\TwViewhelpers\Service;

use Doctrine\DBAL\ArrayParameterType;
use Doctrine\DBAL\Exception;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\RootlineUtility;

/**
 * PageService
 *
 * @category   Tollwerk
 * @package    Tollwerk\TwViewhelpers
 * @subpackage Tollwerk\TwViewhelpers\Service
 * @author     tollwerk GmbH <info@tollwerk.de>
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 * @link       https://tollwerk.de
 */
class PageService
{
    /**
     * Get latest change timestamp of page and content elements of that page
     *
     * @param int $pageUid Page UID
     *
     * @return int
     *
     * @throws Exception
     */
    public function getLatestTimestamp(int $pageUid): int
    {
        // Get timestamps of the page itself.
        $queryBuilder = GeneralUtility::makeInstance(
            ConnectionPool::class
        )->getQueryBuilderForTable('pages');
        $page = $queryBuilder
            ->select('tstamp', 'lastUpdated')
            ->from('pages')
            ->where($queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($pageUid)))
            ->executeQuery()
            ->fetchAssociative();

        //If page is created but still disabled with no content
        if (!$page) {
            return 0;
        }

        // If page.lastUpdated was set, always return that.
        if (!empty($page['lastUpdated'])) {
            return $page['lastUpdated'];
        }

        // Get timestamps of all content elements of the page.
        $queryBuilder = GeneralUtility::makeInstance(
            ConnectionPool::class
        )->getQueryBuilderForTable('tt_content');
        $queryBuilder->setRestrictions($queryBuilder->getRestrictions()->removeAll());
        $tstamps = $queryBuilder
            ->select('tstamp')
            ->from('tt_content')
            ->where($queryBuilder->expr()->eq('pid', $queryBuilder->createNamedParameter($pageUid)))
            ->executeQuery()
            ->fetchFirstColumn();

        // Return the latest timestamp.
        $tstamps[] = $page['tstamp'];
        rsort($tstamps);
        return $tstamps[0];
    }

    /**
     * Get `author` and `author_email` page properties, starting at the current page and traversing up the rootline.
     * Returns the first instance where both `author` and `author_email` are set. If one of them is missing, the
     * page will be skipped.
     *
     * @param int $pageUid Page UID
     *
     * @return array|null
     *
     * @throws Exception
     */
    public function getAuthor(int $pageUid): ?array
    {
        // Get rootline page UIDs.
        $rootlineUtility = GeneralUtility::makeInstance(RootlineUtility::class, $pageUid);
        $rootline = $rootlineUtility->get();
        $rootlineUids = [];
        foreach ($rootline as $page) {
            $rootlineUids[] = $page['uid'];
        }

        // Get page
        $queryBuilder = GeneralUtility::makeInstance(
            ConnectionPool::class
        )->getQueryBuilderForTable('pages');
        $pages = $queryBuilder->select('uid', 'author', 'author_email')
            ->from('pages')
            ->where($queryBuilder->expr()->in('uid', $queryBuilder->createNamedParameter($rootlineUids, ArrayParameterType::INTEGER)))
            ->executeQuery()
            ->fetchAllAssociative();

        // Get Author by page UID. Skip pages with missing information.
        $authorByPageUid = [];
        foreach ($pages as $page) {
            if (empty($page['author']) || empty($page['author_email'])) {
                continue;
            }

            $authorByPageUid[$page['uid']] = $page;
        }

        // If no author information was found at all, return null.
        if (!count($authorByPageUid)) {
            return null;
        }

        // Traverse rootline from bottom to top and return first found author information.
        foreach ($rootlineUids as $rootlineUid) {
            if (array_key_exists($rootlineUid, $authorByPageUid)) {
                return [
                    'name' => $authorByPageUid[$rootlineUid]['author'],
                    'email' => $authorByPageUid[$rootlineUid]['author_email']
                ];
            }
        }

        return null;
    }

    /**
     * Get information about last change to the current (or given) page and it's contents
     *
     * @param int $pageUid Page UID
     *
     * @return array
     *
     * @throws Exception
     */
    public function getLastUpdate(int $pageUid): array
    {
        return [
            'page' => [
                'uid' => $pageUid,
                'tstamp' => $this->getLatestTimestamp($pageUid),
            ],
            'author' => $this->getAuthor($pageUid),
        ];
    }
}
