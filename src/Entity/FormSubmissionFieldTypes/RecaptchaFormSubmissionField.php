<?php

namespace Hgabka\KunstmaanExtensionBundle\Entity\FormSubmissionFieldTypes;

use Doctrine\ORM\Mapping as ORM;
use Hgabka\KunstmaanExtensionBundle\Form\PageParts\RecaptchaPagePartAdminType;
use Kunstmaan\FormBundle\Entity\FormSubmissionField;

/**
 * The RecaptchaFormSubmissionField can be used to store recaptcha values to a FormSubmission.
 *
 * @ORM\Entity
 * @ORM\Table(name="hg_kuma_extension_recaptcha_form_submission_fields")
 */
class RecaptchaFormSubmissionField extends FormSubmissionField
{
    /**
     * @ORM\Column(name="efsf_value", type="string")
     */
    protected $value;

    /**
     * Return a string representation of this FormSubmissionField.
     *
     * @return string
     */
    public function __toString()
    {
        $value = $this->getValue();

        return !empty($value) ? $value : '';
    }

    /**
     * Returns the default form type for this FormSubmissionField.
     *
     * @return string
     */
    public function getDefaultAdminType()
    {
        return RecaptchaPagePartAdminType::class;
    }

    /**
     * Returns the current value of this field.
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Sets the current value of this field.
     *
     * @param string $value
     *
     * @return RecaptchaFormSubmissionField
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }
}
