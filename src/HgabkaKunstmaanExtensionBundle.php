<?php

namespace Hgabka\KunstmaanExtensionBundle;

use Doctrine\DBAL\Types\Type;
use Hgabka\KunstmaanExtensionBundle\Doctrine\Type\LongblobType;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class HgabkaKunstmaanExtensionBundle extends Bundle
{
    public function boot()
    {
        foreach ($this->container->getParameter('doctrine.entity_managers') as $name => $serviceName) {
            $em = $this->container->get($serviceName);
            if (!Type::hasType(LongblobType::TYPE)) {
                Type::addType(LongblobType::TYPE, LongblobType::class);
                $em->getConnection()->getDatabasePlatform()->registerDoctrineTypeMapping('longblob', LongblobType::TYPE);
            }
        }
    }
}
