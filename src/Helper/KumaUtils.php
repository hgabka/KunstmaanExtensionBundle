<?php
namespace Hgabka\KunstmaanExtensionBundle\Helper

use Kunstmaan\AdminBundle\Helper\DomainConfiguration;
use Symfony\Component\HttpFoundation\RequestStack;

class KumaUtils
{
    /** @var  DomainConfiguration */
    protected $domainConfiguration;

    /** @var  RequestStack */
    protected $requestStack;
    /**
     * KumaUtils constructor.
     */
    public function __construct(DomainConfiguration $domainConfiguration, RequestStack $requestStack)
    {
        $this->domainConfiguration = $domainConfiguration;
        $this->requestStack = $requestStack;
    }

    public function getCurrentLocale(bool $frontend = true)
    {
        $request = $this->requestStack->getMasterRequest();

        $locale = $request ? $request->getLocale() : null;

        $availableLocales = $frontend ? $this->domainConfiguration->getFrontendLocales() : $this->domainConfiguration->getBackendLocales();
        if (!empty($locale) && in_array($locale, $availableLocales)) {
            return $locale;
        }

        return $this->domainConfiguration->getDefaultLocale();
    }
}