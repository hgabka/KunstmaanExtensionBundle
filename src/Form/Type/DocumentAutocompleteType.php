<?php

namespace Hgabka\KunstmaanExtensionBundle\Form\Type;

class DocumentAutocompleteType extends ObjectAutocompleteType
{
    public function getBlockPrefix()
    {
        return 'document_autocomplete';
    }
}
