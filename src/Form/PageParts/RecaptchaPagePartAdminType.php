<?php

/*
 * This file is part of PHP CS Fixer.
 * (c) Fabien Potencier <fabien@symfony.com>
 *     Dariusz Rumiński <dariusz.ruminski@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Hgabka\KunstmaanExtensionBundle\Form\PageParts;

use Hgabka\KunstmaanExtensionBundle\Entity\PageParts\RecaptchaPagePart;
use Hgabka\KunstmaanExtensionBundle\Form\Type\RecaptchaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * RecaptchaPagePartAdminType.
 */
class RecaptchaPagePartAdminType extends AbstractType
{
    /**
     * Builds the form.
     *
     * This method is called for each type in the hierarchy starting form the
     * top most type. Type extensions can further modify the form.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     *
     * @see FormTypeExtensionInterface::buildForm()
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $recaptchaType = new RecaptchaType('6LdEOhMUAAAAAC-Ir9F1fknqtCoyzSWmoqu0395J');
        $builder->add('content', $recaptchaType, [
            'attr' => ['class' => '', 'height' => 140],
        ]);
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getBlockPrefix()
    {
        return 'hgabka_kunstmaan_extensionbundle_recaptchapageparttype';
    }

    /**
     * Sets the default options for this type.
     *
     * @param OptionsResolver $resolver the resolver for the options
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RecaptchaPagePart::class,
        ]);
    }
}
