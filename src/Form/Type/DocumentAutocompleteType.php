<?php
/**
 * Created by PhpStorm.
 * User: Gabe
 * Date: 2016.08.16.
 * Time: 15:32
 */

namespace Hgabka\KunstmaanExtensionBundle\Form\Type;

class DocumentAutocompleteType extends ObjectAutocompleteType
{
    public function getBlockPrefix()
    {
        return 'document_autocomplete';
    }
}