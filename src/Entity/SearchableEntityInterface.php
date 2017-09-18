<?php

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
