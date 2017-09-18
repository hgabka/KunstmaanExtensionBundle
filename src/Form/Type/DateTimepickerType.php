<?php

namespace Hgabka\KunstmaanExtensionBundle\Form\Type;

use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;

class DateTimepickerType extends DatepickerType
{
    protected $jsOpts = [
        'format' => 'YYYY-MM-DD HH:mm:ss',
    ];

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer(new CallbackTransformer(
            function ($transform) {
                if (null !== $transform) {
                    /** @var $transform \DateTime */
                    $transform = $transform->format('Y-m-d H:i:s');
                }

                return $transform;
            },
            function ($reverse) {
                if (null !== $reverse) {
                    $reverse = new \DateTime($reverse);
                }

                return $reverse;
            }
        ));
    }

    public function getParent()
    {
        return TextType::class;
    }

    public function getBlockPrefix()
    {
        return 'datetimepicker';
    }
}
