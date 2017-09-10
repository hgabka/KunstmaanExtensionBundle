<?php

/*
 * This file is part of PHP CS Fixer.
 * (c) Fabien Potencier <fabien@symfony.com>
 *     Dariusz Rumiński <dariusz.ruminski@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Hgabka\KunstmaanExtensionBundle\Entity\PageParts;

use Doctrine\ORM\Mapping as ORM;

/**
 * InsertPagePagePart.
 *
 * @ORM\Table(name="hg_kuma_extension_insert_page_page_parts")
 * @ORM\Entity
 */
class InsertPagePagePart extends \Kunstmaan\PagePartBundle\Entity\AbstractPagePart
{
    /**
     * @var \Kunstmaan\NodeBundle\Entity\Node
     *
     * @ORM\ManyToOne(targetEntity="Kunstmaan\NodeBundle\Entity\Node")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="node_id", referencedColumnName="id")
     * })
     */
    private $node;

    /**
     * Set node.
     *
     * @param \Kunstmaan\NodeBundle\Entity\Node $node
     *
     * @return InsertPagePagePart
     */
    public function setNode(\Kunstmaan\NodeBundle\Entity\Node $node = null)
    {
        $this->node = $node;

        return $this;
    }

    /**
     * Get node.
     *
     * @return \Kunstmaan\NodeBundle\Entity\Node
     */
    public function getNode()
    {
        return $this->node;
    }

    /**
     * Get the twig view.
     *
     * @return string
     */
    public function getDefaultView()
    {
        return 'HgabkaKunstmaanExtensionBundle:PageParts:InsertPagePagePart/view.html.twig';
    }

    /**
     * Get the admin form type.
     *
     * @return \Hgabka\KunstmaanExtensionBundle\Form\PageParts\InsertPagePagePartAdminType
     */
    public function getDefaultAdminType()
    {
        return 'hgabka_kunstmaan_extension.page_insert.admin_form_type';
    }
}
