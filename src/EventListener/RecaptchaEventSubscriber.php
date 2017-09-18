<?php

namespace Hgabka\KunstmaanExtensionBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Hgabka\KunstmaanExtensionBundle\Entity\FormSubmissionFieldTypes\RecaptchaFormSubmissionField;

class RecaptchaEventSubscriber implements EventSubscriber
{
    public function getSubscribedEvents()
    {
        return [
            'loadClassMetadata',
        ];
    }

    public function loadClassMetadata(LoadClassMetadataEventArgs $args)
    {
        $metaData = $args->getClassMetadata();
        if (RecaptchaFormSubmissionField::class === $metaData->getName()) {
            $metaData->addDiscriminatorMapClass('recaptcha', $metaData->getName());
        }
    }
}
