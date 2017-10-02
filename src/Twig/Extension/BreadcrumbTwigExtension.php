<?php

namespace Hgabka\KunstmaanExtensionBundle\Twig\Extension;

use Hgabka\KunstmaanExtensionBundle\Breadcrumb\BreadcrumbManager;

class BreadcrumbTwigExtension extends \Twig_Extension implements \Twig_Extension_GlobalsInterface
{
    /**
     * @var BreadcrumbManager
     */
    protected $breadcrumbManager;

    /**
     * PublicTwigExtension constructor.
     *
     * @param BreadcrumbManager $manager
     *
     */
    public function __construct(BreadcrumbManager $manager)
    {
        $this->breadcrumbManager = $manager;
    }

    public function getGlobals()
    {
        return ['breadcrumb_manager' => $this->breadcrumbManager];
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'hgabka_kunstmaanextensionbundle_breadcrumb_twig_extension';
    }
}
