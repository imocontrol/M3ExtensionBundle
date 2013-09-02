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
namespace IMOControl\M3\ExtensionBundle\Generator;

use Symfony\Component\Console\Output\OutputInterface;
use IMOControl\M3\ExtensionBundle\Bundle\BundleMetadata;

class BundleGenerator implements GeneratorInterface
{
    protected $bundleTemplate;

    public function __construct()
    {
        $this->bundleTemplate = file_get_contents(__DIR__.'/../Resources/skeleton/bundle/bundle.mustache');
    }

    /**
     * @param OutputInterface $output
     * @param BundleMetadata  $bundleMetadata
     */
    public function generate(OutputInterface $output, BundleMetadata $bundleMetadata)
    {
        $this->generateBundleDirectory($output, $bundleMetadata);
        $this->generateBundleFile($output, $bundleMetadata);
    }

    /**
     * @param OutputInterface $output
     * @param BundleMetadata  $bundleMetadata
     */
    protected function generateBundleDirectory(OutputInterface $output, BundleMetadata $bundleMetadata)
    {
        $directories = array(
            '',
            'Admin',
            'Resources/config/routing',
            'Resources/views',
            'Command',
            'DependencyInjection',
            'Entity',
            'Entity/Repository',
            'Document',
            'PHPCR',
            'Controller'
        );

        foreach ($directories as $directory) {
            $dir = sprintf('%s/%s', $bundleMetadata->getExtendedDirectory(), $directory);
            if (!is_dir($dir)) {
                $output->writeln(sprintf('  > generating bundle directory <comment>%s</comment>', $dir));
                mkdir($dir, 0755, true);
            }
        }
    }

    /**
     * @param OutputInterface $output
     * @param BundleMetadata  $bundleMetadata
     */
    protected function generateBundleFile(OutputInterface $output, BundleMetadata $bundleMetadata)
    {
        $file = sprintf('%s/Application%s.php', $bundleMetadata->getExtendedDirectory(), $bundleMetadata->getFullname());

        if (is_file($file)) {
            return;
        }

        $output->writeln(sprintf('  > generating bundle file <comment>%s</comment>', $file));

        $string = Mustache::replace($this->getBundleTemplate(), array(
            'bundle'    => $bundleMetadata->getFullname(),
            'namespace' => $bundleMetadata->getExtendedNamespace(),
        ));

        file_put_contents($file, $string);
    }

    /**
     * @return string
     */
    protected function getBundleTemplate()
    {
        return $this->bundleTemplate;
    }
}
