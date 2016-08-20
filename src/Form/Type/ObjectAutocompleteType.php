<?php
/**
 * Created by PhpStorm.
 * User: Gabe
 * Date: 2016.08.16.
 * Time: 14:16
 */

namespace Hgabka\KunstmaanExtensionBundle\Form\Type;

use Doctrine\Common\Persistence\AbstractManagerRegistry;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Hgabka\KunstmaanExtensionBundle\Form\Transformer\ObjectAutocompleteViewTransformer;
use Hgabka\KunstmaanExtensionBundle\Form\Transformer\ObjectAutocompleteModelTransformer;
use Symfony\Bridge\Doctrine\Form\EventListener\MergeDoctrineCollectionListener;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class ObjectAutocompleteType extends AbstractType
{
    /**
     * @var AbstractManagerRegistry $doctrine
     */
    private $registry;

    public function __construct(AbstractManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->addEventSubscriber(new MergeDoctrineCollectionListener())
            ->addViewTransformer(new ObjectAutocompleteViewTransformer(
                $this->registry->getManager()->getRepository($options['class']), $options),
                true
            )
        ;

        $builder->add('title', TextType::class, ['attr' => $options['attr']]);
        $builder->add('items', CollectionType::class, [
            'entry_type' => ObjectAutocompleteItemType::class,
            'entry_options' => [
                'repository' => $this->registry->getManager()->getRepository($options['class']),
                'to_string_callback' =>$options['to_string_callback'],
            ],
            'allow_add' => true,
            'allow_delete' => true,
            'attr' => array_merge($options['attr'], [
                'data-maximum-items' => $options['maximum_items'],
            ]),
        ]);
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        parent::buildView($view, $form, $options);
        $view->vars['placeholder'] = $options['placeholder'];
        $view->vars['minimum_input_length'] = $options['minimum_input_length'];
        $view->vars['maximum_items'] = $options['maximum_items'];

        // ajax parameters
        $view->vars['url'] = $options['url'];
        $view->vars['route'] = $options['route'];
    }

    public function getParent()
    {
        return 'form';
    }

    public function getBlockPrefix()
    {
        return 'object_autocomplete';
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'attr' => [],
            'compound' => true,
            'placeholder' => '',
            'minimum_input_length' => 0,
            'maximum_items' => null,
            'to_string_callback' => null,
            'url' => '',
            'route' => [
                'name' => '',
                'parameters' => [],
            ],
        ]);

        $resolver->setRequired(['class']);
    }
}