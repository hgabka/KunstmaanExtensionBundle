<?php

/*
 * This file is part of PHP CS Fixer.
 * (c) Fabien Potencier <fabien@symfony.com>
 *     Dariusz Rumiński <dariusz.ruminski@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Hgabka\KunstmaanExtensionBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StaticControlType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
          'required' => false,
          'disabled' => true,
          'is_html' => false,
          'format' => '%s',
          'date_format' => null,
        ]);
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['is_html'] = $options['is_html'];
    }

    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        $isDate = false;
        $val = $view->vars['value'];
        if ($val instanceof \DateTime) {
            $isDate = true;
        } else {
            $val = sprintf($options['format'], $val);
        }

        $view->vars['is_date'] = $isDate;
        $view->vars['date_format'] = $options['date_format'];
        $view->vars['value'] = $val;
    }

    public function getParent()
    {
        return 'text';
    }

    public function getBlockPrefix()
    {
        return 'hgabka_kunstmaanextension_plain';
    }
}
