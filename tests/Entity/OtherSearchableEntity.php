<?php
/**
 * Created by IntelliJ IDEA.
 * User: chris
 * Date: 2016.05.05.
 * Time: 11:41
 */

namespace Webtown\KunstmaanExtensionBundle\Tests\Entity;

use Doctrine\ORM\Mapping as ORM;
use Kunstmaan\AdminBundle\Entity\AbstractEntity;
use Kunstmaan\NodeSearchBundle\Helper\SearchBoostInterface;
use Webtown\KunstmaanExtensionBundle\Entity\SearchableEntityInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="webtown_kunstmaan_slider_image")
 */
class OtherSearchableEntity extends AbstractEntity implements
    SearchableEntityInterface,
    SearchBoostInterface
{
    /**
     * Name
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false, options={
     *          "comment": "Name"})
     */
    protected $name;

    public function __construct($id = 1)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param string|null $locale
     * @return string
     */
    public function getSearchTitle($locale = null)
    {
        return $this->getName();
    }

    /**
     * @param string|null $locale
     * @return string
     */
    public function getSearchContent($locale = null)
    {
        return $this->getName();
    }

    /**
     * @return string Translation key!
     */
    public function getSearchType()
    {
        return 'searchable.test.other_entity';
    }

    /**
     * @return string
     */
    public function getSearchRouteName()
    {
        return 'other_search_entity_show';
    }

    /**
     * @return array
     */
    public function getSearchRouteParameters()
    {
        return [
            'id' => $this->getId(),
        ];
    }

    /**
     * Use in create doc UID.
     *
     * @return string
     */
    public function getSearchUniqueEntityName()
    {
        return 'other_searchable_test_entity';
    }

    /**
     * @return float
     */
    public function getSearchBoost()
    {
        return 2;
    }
}
