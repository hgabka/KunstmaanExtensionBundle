<?php

/*
 * This file is part of PHP CS Fixer.
 * (c) Fabien Potencier <fabien@symfony.com>
 *     Dariusz Rumiński <dariusz.ruminski@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Hgabka\KunstmaanExtensionBundle\Entity\PageParts;

use ArrayObject;
use Doctrine\ORM\Mapping as ORM;
use Hgabka\KunstmaanExtensionBundle\Entity\FormSubmissionFieldTypes\RecaptchaFormSubmissionField;
use Hgabka\KunstmaanExtensionBundle\Form\PageParts\RecaptchaPagePartAdminType;
use Hgabka\KunstmaanExtensionBundle\Form\RecaptchaFormSubmissionType;
use Kunstmaan\FormBundle\Entity\PageParts\AbstractFormPagePart;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * RecaptchaPagePart.
 *
 * @ORM\Table(name="hg_kuma_extension_recaptcha_page_parts")
 * @ORM\Entity
 */
class RecaptchaPagePart extends AbstractFormPagePart
{
    public $content;

    public $required = false;

    /**
     * Get the twig view.
     *
     * @return string
     */
    public function getDefaultView()
    {
        return 'HgabkaKunstmaanExtensionBundle:PageParts:RecaptchaPagePart/view.html.twig';
    }

    /**
     * Modify the form with the fields of the current page part.
     *
     * @param FormBuilderInterface $formBuilder The form builder
     * @param ArrayObject          $fields      The fields
     * @param int                  $sequence    The sequence of the form field
     */
    public function adaptForm(FormBuilderInterface $formBuilder, ArrayObject $fields, $sequence)
    {
        $efsf = new RecaptchaFormSubmissionField();
        $efsf->setFieldName('field_'.$this->getUniqueId());
        $efsf->setLabel('Recaptcha');
        $efsf->setSequence($sequence);

        $data = $formBuilder->getData();
        $data['formwidget_'.$this->getUniqueId()] = $efsf;

        $formBuilder->add(
            'formwidget_'.$this->getUniqueId(),
            RecaptchaFormSubmissionType::class,
            [
                'label' => $this->getLabel(),
            ]
        );
        $formBuilder->setData($data);

        $fields->append($efsf);
    }

    /**
     * Get the admin form type.
     *
     * @return RecaptchaPagePartAdminType
     */
    public function getDefaultAdminType()
    {
        return new RecaptchaPagePartAdminType();
    }
}
