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

    /**
     * @param bool $frontend
     * @return null|string
     */
    public function getCurrentLocale(bool $frontend = true) : ?string
    {
        $request = $this->requestStack->getMasterRequest();

        $locale = $request ? $request->getLocale() : null;

        $availableLocales = $this->getAvailableLocales($frontend);
        if (!empty($locale) && in_array($locale, $availableLocales)) {
            return $locale;
        }

        return $this->domainConfiguration->getDefaultLocale();
    }

    /**
     * @param bool $frontend
     * @return array
     */
    public function getAvailableLocales(bool $frontend = true) : array
    {
        return $frontend ? $this->domainConfiguration->getFrontendLocales() : $this->domainConfiguration->getBackendLocales();
    }
}