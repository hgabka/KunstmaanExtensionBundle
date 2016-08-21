<?php
/**
 * Created by PhpStorm.
 * User: Gábor
 * Date: 2016. 08. 20.
 * Time: 15:43
 */

namespace Hgabka\KunstmaanExtensionBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DatepickerType extends AbstractType
{
    protected $jsOpts = [
        'format' => 'YYYY-MM-DD',
        'js-options' => [],
    ];

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer(new CallbackTransformer(
            function ($transform) {
                if ($transform !== null) {
                    /** @var $transform \DateTime */
                    $transform = $transform->format('Y-m-d');
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

    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        if (!isset($options['attr']['data-options']))
        {
            $options['attr']['data-options'] = json_encode($options['js-options']);
        }
        foreach (array_keys($this->jsOpts) as $optName) {
            $view->vars[$optName] = $options[$optName];
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults($this->jsOpts);
    }

    public function getParent()
    {
        return TextType::class;
    }

    public function getBlockPrefix()
    {
        return 'datepicker';
    }
}