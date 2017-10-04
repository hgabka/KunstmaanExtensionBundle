<?php

namespace Hgabka\KunstmaanExtensionBundle\Entity\PageParts;

use Doctrine\ORM\Mapping as ORM;
use Hgabka\KunstmaanExtensionBundle\Form\PageParts\GalleryPagePartAdminType;
use Kunstmaan\MediaBundle\Entity\Folder;
use Kunstmaan\PagePartBundle\Entity\AbstractPagePart;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * GalleryPagePart.
 *
 * @ORM\Table(name="hg_kuma_extension_gallery_page_parts")
 * @ORM\Entity
 */
class GalleryPagePart extends AbstractPagePart
{
    /**
     * @var Folder
     *
     * @ORM\ManyToOne(targetEntity="\Kunstmaan\MediaBundle\Entity\Folder")
     * @ORM\JoinColumn(name="folder_id", referencedColumnName="id", nullable=false)
     * @Assert\NotBlank()
     */
    protected $folder;

    /**
     * Get the twig view.
     *
     * @return string
     */
    public function getDefaultView()
    {
        return 'HgabkaKunstmaanExtensionBundle:PageParts:GalleryPagePart/view.html.twig';
    }

    /**
     * Get the admin form type.
     *
     * @return string
     */
    public function getDefaultAdminType()
    {
        return GalleryPagePartAdminType::class;
    }

    /**
     * Set folder.
     *
     * @param Folder $folder
     *
     * @return GalleryPagePart
     */
    public function setFolder(Folder $folder)
    {
        $this->folder = $folder;

        return $this;
    }

    /**
     * Get folder.
     *
     * @return Folder
     */
    public function getFolder()
    {
        return $this->folder;
    }

    public function getMedia()
    {
        return $this->getFolder() ? $this->getFolder()->getMedia() : [];
    }
}
