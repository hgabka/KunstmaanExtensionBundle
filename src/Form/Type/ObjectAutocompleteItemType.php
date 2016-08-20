<?php
/**
 * Created by PhpStorm.
 * User: Gabe
 * Date: 2016.08.16.
 * Time: 14:16
 */

namespace Hgabka\KunstmaanExtensionBundle\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\AbstractType;

class ObjectAutocompleteItemType extends AbstractType
{
    public function getParent()
    {
        return HiddenType::class;
    }

    public function getBlockPrefix()
    {
        return 'object_autocomplete_item';
    }
}
