<?php

/**
 * This file is part of the psc7-helper/psc7-helper.
 *
 * @see https://github.com/PSC7-Helper/psc7-helper
 *
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace psc7helper\Module\Tools_corruptimages;

use psc7helper\App\Models\Model_Abstract;
use psc7helper\App\Models\Model_Interface;

class Model extends Model_Abstract implements Model_Interface
{
    /**
     * getImageList.
     *
     * @return array
     */
    public function getImageList()
    {
        $data = $this->database->selectAssoc(
            '
            SELECT
                sai.`img`, sai.`extension`
            FROM
                `PREFIX_s_articles_img` AS sai
            LEFT JOIN
                `PREFIX_s_articles_details` AS sad
            ON
                sad.`articleID` = sai.`articleID`
            WHERE
                sai.`articleID` IS NOT NULL
                AND sai.`img` IS NOT NULL
                AND sad.`articleID` IS NOT NULL
            ORDER BY
                sai.`id` DESC
            '
        );
        if ($data) {
            return $data;
        }

        return false;
    }

    /**
     * getIDFromImage.
     *
     * @param string $image
     *
     * @return string
     */
    public function getIDFromImage($image)
    {
        $img = explode('.', $image);
        if (count($img) > 0) {
            $image = $img[0];
        }
        $data = $this->database->selectVar(
            '
            SELECT
                `id`
            FROM
                `PREFIX_s_articles_img`
            WHERE
                `img`= ?
            LIMIT
                1
            ',
            [
                $image,
            ]
        );
        if ($data) {
            return $data;
        }

        return false;
    }

    /**
     * getArticleIDFromImage.
     *
     * @param string $image
     *
     * @return string
     */
    public function getArticleIDFromImage($image)
    {
        $img = explode('.', $image);
        if (count($img) > 0) {
            $image = $img[0];
        }
        $data = $this->database->selectVar(
            '
            SELECT
                `articleID`
            FROM
                `PREFIX_s_articles_img`
            WHERE
                `img`= ?
            LIMIT
                1
            ',
            [
                $image,
            ]
        );
        if ($data) {
            return $data;
        }

        return false;
    }

    /**
     * getStatusByArticleID.
     *
     * @param string $articleID
     *
     * @return string
     */
    public function getStatusByArticleID($articleID)
    {
        $data = $this->database->selectVar(
            '
            SELECT
                `active`
            FROM
                `PREFIX_s_articles`
            WHERE
                `id`= ?
            LIMIT
                1
            ',
            [
                $articleID,
            ]
        );
        if ($data) {
            return (int) $data;
        }

        return false;
    }

    /**
     * getOrdnernumberByArticleID.
     *
     * @param string $articleID
     *
     * @return string
     */
    public function getOrdnernumberByArticleID($articleID)
    {
        $data = $this->database->selectVar(
            '
            SELECT
                `ordernumber`
            FROM
                `PREFIX_s_articles_details`
            WHERE
                `articleID`= ?
            LIMIT
                1
            ',
            [
                $articleID,
            ]
        );
        if ($data) {
            return $data;
        }
        $data = $this->database->selectVar(
            '
            SELECT
                `id`
            FROM
                `PREFIX_s_articles`
            WHERE
                `id`= ?
            LIMIT
                1
            ',
            [
                $articleID,
            ]
        );
        if ($data) {
            return 'no article details';
        }

        return false;
    }

    /**
     * getPlentyIDByArticleID.
     *
     * @param string $articleID
     *
     * @return string
     */
    public function getPlentyIDByArticleID($articleID)
    {
        $oi = $this->database->selectVar(
            "
            SELECT
                `objectIdentifier`
            FROM
                `PREFIX_plenty_identity`
            WHERE
                `adapterIdentifier`= ?
                AND `objectType` = 'Product'
            LIMIT
                1
            ",
            [
                $articleID,
            ]
        );
        if (! $oi) {
            return 'not mapped';
        }
        $data = $this->database->selectVar(
            '
            SELECT
                `adapterIdentifier`
            FROM
                `PREFIX_plenty_identity`
            WHERE
                `objectIdentifier`= ?
            LIMIT
                1
            ',
            [
                $oi,
            ]
        );
        if ($data) {
            return $data;
        } else {
            return 'not mapped';
        }

        return false;
    }

    /**
     * getObjectIdentifier.
     *
     * @param string $adapterIdentifier
     * @param string $adapterName
     *
     * @return string
     */
    public function getObjectIdentifier($adapterIdentifier, $adapterName)
    {
        $adapter = 'ShopwareAdapter';
        switch ($adapterName) {
            case 'Plenty':
                $adapter = 'PlentymarketsAdapter';
                break;
            case 'Shopware':
                $adapter = 'ShopwareAdapter';
                break;
        }
        $data = $this->database->selectVar(
            "
            SELECT
                `objectIdentifier`
            FROM
                `PREFIX_plenty_identity`
            WHERE
                `adapterIdentifier`= ?
                AND `adapterName` = ?
                AND `objectType` = 'Product'
            LIMIT
                1
            ",
            [
                $adapterIdentifier,
                $adapter,
            ]
        );
        if ($data) {
            return $data;
        }

        return false;
    }
}
