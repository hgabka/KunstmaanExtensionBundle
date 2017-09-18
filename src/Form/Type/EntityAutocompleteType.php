<?php

namespace Hgabka\KunstmaanExtensionBundle\Form\Type;

class EntityAutocompleteType extends ObjectAutocompleteType
{
    public function getBlockPrefix()
    {
        return 'entity_autocomplete';
    }
}
