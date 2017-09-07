<?php

namespace Hgabka\KunstmaanExtensionBundle\Entity;

use Prezent\Doctrine\Translatable\Annotation as Prezent;
use Prezent\Doctrine\Translatable\Entity\TranslatableTrait as BaseTranslatableTrait;

trait TranslatableTrait
{
    use BaseTranslatableTrait;

    /**
     * @Prezent\CurrentLocale
     */
    private $currentLocale;

    /**
     * Cache current translation. Useful in Doctrine 2.4+
     */
    private $currentTranslation;

    public function getCurrentLocale()
    {
        return $this->currentLocale;
    }

    public function setCurrentLocale($locale)
    {
        $this->currentLocale = $locale;
        return $this;
    }

    /**
     * Translation helper method
     */
    public function translate($locale = null)
    {
        if (null === $locale) {
            $locale = $this->currentLocale;
        }

        if (!$locale) {
            throw new \RuntimeException('No locale has been set and currentLocale is empty');
        }

        if ($this->currentTranslation && $this->currentTranslation->getLocale() === $locale) {
            return $this->currentTranslation;
        }

        if (!$translation = $this->translations->get($locale)) {
            $class = self::getTranslationEntityClass();
            $translation = new $class;
            $translation->setLocale($locale);
            $this->addTranslation($translation);
        }

        $this->currentTranslation = $translation;
        return $translation;
    }
}