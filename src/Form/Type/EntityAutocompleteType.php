<?php
/**
 * Created by PhpStorm.
 * User: Gabe
 * Date: 2016.08.16.
 * Time: 14:16
 */

namespace Hgabka\KunstmaanExtensionBundle\Form\Type;

class EntityAutocompleteType extends ObjectAutocompleteType
{
    public function getBlockPrefix()
    {
        return 'entity_autocomplete';
    }
}