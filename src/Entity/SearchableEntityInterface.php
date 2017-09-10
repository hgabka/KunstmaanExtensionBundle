<?php

/*
 * This file is part of PHP CS Fixer.
 * (c) Fabien Potencier <fabien@symfony.com>
 *     Dariusz Rumiński <dariusz.ruminski@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Hgabka\KunstmaanExtensionBundle\Entity;

interface SearchableEntityInterface
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @param null|string $locale
     *
     * @return string
     */
    public function getSearchTitle($locale = null);

    /**
     * @param null|string $locale
     *
     * @return string
     */
    public function getSearchContent($locale = null);

    /**
     * @return string Translation key!
     */
    public function getSearchType();

    /**
     * @return string
     */
    public function getSearchRouteName();

    /**
     * @return array
     */
    public function getSearchRouteParameters();

    /**
     * Use in create doc UID.
     *
     * @return string
     */
    public function getSearchUniqueEntityName();
}
