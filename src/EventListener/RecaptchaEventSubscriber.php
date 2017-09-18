<?php

/*
 * This file is part of PHP CS Fixer.
 * (c) Fabien Potencier <fabien@symfony.com>
 *     Dariusz Rumiński <dariusz.ruminski@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

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
