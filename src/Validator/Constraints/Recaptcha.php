<?php

namespace Hgabka\KunstmaanExtensionBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Recaptcha extends Constraint
{
    public $message = 'wt_kuma_extension.recaptcha.message';
}
