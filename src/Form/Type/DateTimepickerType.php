<?php
/**
 * Created by PhpStorm.
 * User: Gábor
 * Date: 2016. 08. 20.
 * Time: 15:44
 */

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
                if ($transform !== null) {
                    /** @var $transform \DateTime */
                    $transform = $transform->format('Y-m-d H:i:s');
                }

                return $transform;
            },
            function ($reverse) {
                if ($reverse !== null) {
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