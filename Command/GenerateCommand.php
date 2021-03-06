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
namespace IMOControl\M3\ExtensionBundle\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\Output;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

use IMOControl\M3\ExtensionBundle\Bundle\BundleMetadata;

/**
 * Generate Application entities from bundle entities
 *
 * @author Thomas Rabaix <thomas.rabaix@sonata-project.org>
 */
class GenerateCommand extends ContainerAwareCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {

        $this
            ->setName('imocontrol:extension:generate')
            ->setHelp(<<<EOT
  <info>example: $ php app/console imocontrol:extension:generate IMOControlM3CustomerBundle</info>
EOT
        );

        $this->setDescription('Applicationify the core iMOControl Bundles to customize each iMOControl bundles.');

        $this->addArgument('bundle', InputArgument::OPTIONAL, 'The bundle name to extends from', false);
        $this->addOption('dest', 'd', InputOption::VALUE_OPTIONAL, 'The base folder where the Application will be created', 'src/');
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dest = $input->getOption('dest');
        if ($dest) {
            $dest = realpath($dest);
        } else {
            $dest = $this->getContainer()->get('kernel')->getRootDir();
        }

        $configuration = array(
            'application_dir' =>  sprintf("%s/Application", $dest)
        );

        $bundleName = $input->getArgument('bundle');

        if ($bundleName == false) {
            $output->writeln('');
            $output->writeln('<error>You must provide a bundle name!</error>');
            $output->writeln('');
            $output->writeln('  Bundles availables :');
            foreach ($this->getContainer()->get('kernel')->getBundles() as $bundle) {
                $bundleMetadata = new BundleMetadata($bundle, $configuration);

                if (!$bundleMetadata->isExtendable()) {
                    continue;
                }

                $output->writeln(sprintf('     - %s', $bundle->getName()));
            }

            $output->writeln('');

            return 0;
        }

        $processed = false;
        foreach ($this->getContainer()->get('kernel')->getBundles() as $bundle) {

            if ($bundle->getName() != $bundleName) {
                continue;
            }

            $processed = true;
            $bundleMetadata = new BundleMetadata($bundle, $configuration);

            // generate the bundle file
            if (!$bundleMetadata->isExtendable()) {
                $output->writeln(sprintf('Ignoring bundle : "<comment>%s</comment>"', $bundleMetadata->getClass()));
                continue;
            }
            // generate the bundle file
            if (!$bundleMetadata->isValid()) {
                $output->writeln(sprintf('%s : <comment>wrong folder structure</comment>', $bundleMetadata->getClass()));
                continue;
            }

            $output->writeln(sprintf('Processing bundle : "<info>%s</info>"', $bundleMetadata->getName()));

            $this->getContainer()->get('imocontrol.extension.generator.bundle')
                ->generate($output, $bundleMetadata);

            $output->writeln(sprintf('Processing Doctrine ORM : "<info>%s</info>"', $bundleMetadata->getName()));
            $this->getContainer()->get('imocontrol.extension.generator.orm')
                ->generate($output, $bundleMetadata);

            /*$output->writeln(sprintf('Processing Doctrine ODM : "<info>%s</info>"', $bundleMetadata->getName()));
            $this->getContainer()->get('imocontrol.extension.generator.odm')
                ->generate($output, $bundleMetadata);

            $output->writeln(sprintf('Processing Doctrine PHPCR : "<info>%s</info>"', $bundleMetadata->getName()));
            $this->getContainer()->get('imocontrol.extension.generator.phpcr')
                ->generate($output, $bundleMetadata);
			// */
            $output->writeln('processed... done!');
        }

        if ($processed) {
            $output->writeln('done!');

            return 0;
        }

        $output->writeln(sprintf('<error>The bundle \'%s\' does not exist or not defined in the kernel file!</error>', $bundleName));

        return -1;
    }
}
