<?php

/*
 * This file is part of PHP CS Fixer.
 * (c) Fabien Potencier <fabien@symfony.com>
 *     Dariusz Rumiński <dariusz.ruminski@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Hgabka\KunstmaanExtensionBundle\Entity\PageParts;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Hgabka\KunstmaanExtensionBundle\Entity\SliderImage;
use Hgabka\KunstmaanExtensionBundle\Form\PageParts\SliderPagePartAdminType;
use Kunstmaan\PagePartBundle\Entity\AbstractPagePart;
use Symfony\Component\Form\AbstractType;

/**
 * ImagePagePart.
 *
 * @ORM\Entity
 * @ORM\Table(name="hg_kuma_extension_slider_page_parts")
 */
class SliderPagePart extends AbstractPagePart
{
    /**
     * @var ArrayCollection|SliderImage[]
     *
     * @ORM\OneToMany(targetEntity="\Hgabka\KunstmaanExtensionBundle\Entity\SliderImage", mappedBy="sliderPagePart", cascade={"persist"})
     * @ORM\OrderBy({"displayOrder" = "ASC"})
     */
    protected $images;

    public function __construct()
    {
        $this->images = new ArrayCollection();
    }

    /**
     * @return ArrayCollection|SliderImage[]
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * @param ArrayCollection|SliderImage[] $images
     *
     * @return $this
     */
    public function setImages($images)
    {
        $this->images = $images;

        return $this;
    }

    public function addImage(SliderImage $image)
    {
        $image->setSliderPagePart($this);
        $this->images->add($image);

        return $this;
    }

    public function removeImage(SliderImage $image)
    {
        $this->images->removeElement($image);

        return $this;
    }

    /**
     * Returns the view used in the frontend.
     *
     * @return string
     */
    public function getDefaultView()
    {
        return 'HgabkaKunstmaanExtensionBundle:PageParts:SliderPagePart/view.html.twig';
    }

    /**
     * Returns the view used in the backend.
     *
     * @return string
     */
    public function getAdminView()
    {
        return 'HgabkaKunstmaanExtensionBundle:PageParts:SliderPagePart/view-admin.html.twig';
    }

    /**
     * @return AbstractType
     */
    public function getDefaultAdminType()
    {
        return new SliderPagePartAdminType();
    }
}
