<?php

namespace Hgabka\KunstmaanExtensionBundle\Helper;

use Kunstmaan\AdminBundle\Helper\DomainConfiguration;
use Kunstmaan\MediaBundle\Entity\Media;
use Symfony\Component\HttpFoundation\RequestStack;

class KumaUtils
{
    /** @var  DomainConfiguration */
    protected $domainConfiguration;

    /** @var  RequestStack */
    protected $requestStack;

    /** @var string  */
    protected $projectDir;

    /**
     * KumaUtils constructor.
     * @param DomainConfiguration $domainConfiguration
     * @param RequestStack $requestStack
     * @param string $projectDir
     */
    public function __construct(DomainConfiguration $domainConfiguration, RequestStack $requestStack, string $projectDir)
    {
        $this->domainConfiguration = $domainConfiguration;
        $this->requestStack = $requestStack;
        $this->projectDir = $projectDir;
    }

    /**
     * @param bool $frontend
     * @return null|string
     */
    public function getCurrentLocale($baseLocale = null, bool $frontend = true)
    {
        $availableLocales = $this->getAvailableLocales($frontend);

        if (!empty($baseLocale) && in_array($baseLocale, $availableLocales)) {
            return $baseLocale;
        }

        $request = $this->requestStack->getMasterRequest();

        $locale = $request ? $request->getLocale() : null;

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

    /**
     * @return string
     */
    public function getDefaultLocale()
    {
        return $this->domainConfiguration->getDefaultLocale();
    }

    /**
     * @return null|\Symfony\Component\HttpFoundation\Request
     */
    public function getMasterRequest()
    {
        return $this->requestStack->getMasterRequest();
    }

    /**
     * @return bool
     */
    public function isMultiLanguage()
    {
        return $this->domainConfiguration->isMultiLanguage();
    }

    /**
     * @param Media $media
     * @return string
     */
    public function getMediaPath(Media $media)
    {
        return $this->projectDir.'/web/'.$media->getUrl();
    }

    /**
     * @param Media $media
     * @return bool|string
     */
    public function getMediaContent(Media $media)
    {
        return file_get_contents($this->getMediaPath($media));
    }
}