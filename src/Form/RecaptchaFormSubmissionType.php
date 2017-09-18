<?php

namespace Hgabka\KunstmaanExtensionBundle\Form;

use Hgabka\KunstmaanExtensionBundle\Entity\FormSubmissionFieldTypes\RecaptchaFormSubmissionField;
use Hgabka\KunstmaanExtensionBundle\Form\Type\RecaptchaType;
use Hgabka\KunstmaanExtensionBundle\Validator\Constraints\Recaptcha;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * The type for the RecaptchaFormSubmissionField.
 */
class RecaptchaFormSubmissionType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('value', HiddenType::class, [
            'constraints' => [new Recaptcha()],
            'required' => false,
            'error_bubbling' => false,
        ]);
        $builder->add('captcha', RecaptchaType::class, ['mapped' => false]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RecaptchaFormSubmissionField::class,
        ]);
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'hgabka_kunstmaan_extensionbundle_recaptchaformsubmissiontype';
    }
}
