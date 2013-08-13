<?php
/*
 * This file is part of the IMOControl project and is a fork of the SonataEasyExtensBundle.
 * which is (c) by Thomas Rabaix <thomas.rabaix@sonata-project.org>.
 * 
 * Modfications done and copyright by:
 * (c) Michael Ofner <michael@imocontrol.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace IMOControl\M3\ExtensionBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Config\FileLocator;

class IMOControlM3ExtensionExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('generator.xml');
    }
}
