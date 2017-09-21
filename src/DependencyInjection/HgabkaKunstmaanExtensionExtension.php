<?php

namespace Hgabka\KunstmaanExtensionBundle\DependencyInjection;

use Hgabka\KunstmaanExtensionBundle\Doctrine\Hydrator\KeyValueHydrator;
use Hgabka\KunstmaanExtensionBundle\DQL\Rand;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class HgabkaKunstmaanExtensionExtension extends Extension implements PrependExtensionInterface, CompilerPassInterface
{
    /** @var string */
    protected $formTypeTemplate = 'HgabkaKunstmaanExtensionBundle:Form:fields.html.twig';

    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $recaptchaTypeDefinition = $container->getDefinition('hgabka_kunstmaan_extension.form.recaptcha_type');
        $recaptchaTypeDefinition->replaceArgument(0, $config['recaptcha']['site_key'] ?? null);

        $recaptchaAdminTypeDefinition = $container->getDefinition('hgabka_kunstmaan_extension.form.recaptcha_admin_type');
        $recaptchaAdminTypeDefinition->replaceArgument(0, $config['recaptcha']['site_key'] ?? null);

        $recaptchaValidatorDefinition = $container->getDefinition('hgabka_kunstmaan_extension.validator.recaptcha');
        $recaptchaValidatorDefinition->replaceArgument(2, $config['recaptcha']['secret'] ?? null);
    }

    /**
     * {@inheritdoc}
     */
    public function prepend(ContainerBuilder $container)
    {
        $configs = $container->getExtensionConfig($this->getAlias());
        $this->processConfiguration(new Configuration(), $configs);
        $this->configureTwigBundle($container);
    }

    protected function configureTwigBundle(ContainerBuilder $container)
    {
        foreach (array_keys($container->getExtensions()) as $name) {
            switch ($name) {
                case 'twig':
                    $container->prependExtensionConfig(
                        $name,
                        ['form_themes' => [$this->formTypeTemplate]]
                    );

                    break;
            }
        }
    }

    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $hydrator = [KeyValueHydrator::HYDRATOR_NAME, KeyValueHydrator::class];
        foreach ($container->getParameter('doctrine.entity_managers') as $name => $serviceName) {
            $definition = $container->getDefinition('doctrine.orm.' . $name . '_configuration');
            $definition->addMethodCall('addCustomHydrationMode', $hydrator);
            $definition->addMethodCall('addCustomNumericFunction', [Rand::FUNCTION_NAME, Rand::class]);
            $definition->addMethodCall('addCustomStringFunction', [Repeat::FUNCTION_NAME, Repeat::class]);
        }
    }
}
