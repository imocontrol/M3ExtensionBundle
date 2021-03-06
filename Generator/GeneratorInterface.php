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

interface GeneratorInterface
{
    /**
     * @param OutputInterface $output
     * @param BundleMetadata  $bundleMetadata
     */
    public function generate(OutputInterface $output, BundleMetadata $bundleMetadata);
}
