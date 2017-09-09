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
     * @param null $baseLocale
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

    /**
     * @return DomainConfiguration
     */
    public function getDomainConfiguration(): DomainConfiguration
    {
        return $this->domainConfiguration;
    }

    /**
     * @return RequestStack
     */
    public function getRequestStack(): RequestStack
    {
        return $this->requestStack;
    }

    /**
     * @return string
     */
    public function getProjectDir(): string
    {
        return $this->projectDir;
    }

    /**
     * @return string
     */
    public function getWebDir(): string
    {
        return $this->projectDir . '/web';
    }

    public static function slugify($text, $subst = '-')
    {
        setlocale(LC_ALL, "hu_HU.utf-8");
        $subst = substr($subst, 0, 1);
        // replace all non letters or digits by -
        $text = preg_replace('~[^\\pL0-9]+~u', $subst, $text); // substitutes anything but letters, numbers and '_' with separator
        $text = trim($text, "-");
        $text = iconv("utf-8", "us-ascii//TRANSLIT", $text); // TRANSLIT does the whole job
        $text = strtolower($text);
        $text = preg_replace('~[^-a-z0-9_' . $subst . ']+~', '', $text); // keep only letters, numbers, '_' and separator  $text = preg_replace('~[^\\pL0-9_]+~u', '-', $text); // substitutes anything but letters, numbers and '_' with separator
        $text = trim($text, "-");
        $text = iconv("utf-8", "us-ascii//TRANSLIT", $text); // TRANSLIT does the whole job
        $text = strtolower($text);
        $text = preg_replace('~[^-a-z0-9_' . $subst . ']+~', '', $text); // keep only letters, numbers, '_' and separator

        if (empty($text)) {
            return '';
        }

        return $text;
    }
}
