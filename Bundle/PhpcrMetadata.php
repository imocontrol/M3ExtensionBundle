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
namespace IMOControl\M3\ExtensionBundle\Bundle;

use Symfony\Component\Finder\Finder;

class PhpcrMetadata
{
    protected $mappingDocumentDirectory;
    protected $extendedMappingDocumentDirectory;
    protected $documentDirectory;
    protected $extendedDocumentDirectory;

    public function __construct(BundleMetadata $bundleMetadata)
    {
        $this->mappingDocumentDirectory           = sprintf('%s/Resources/config/doctrine/', $bundleMetadata->getBundle()->getPath());
        $this->extendedMappingDocumentDirectory   = sprintf('%s/Resources/config/doctrine/', $bundleMetadata->getExtendedDirectory());
        $this->documentDirectory                  = sprintf('%s/PHPCR', $bundleMetadata->getBundle()->getPath());
        $this->extendedDocumentDirectory          = sprintf('%s/PHPCR', $bundleMetadata->getExtendedDirectory());
    }

    public function getMappingDocumentDirectory()
    {
        return $this->mappingDocumentDirectory;
    }

    public function getExtendedMappingDocumentDirectory()
    {
        return $this->extendedMappingDocumentDirectory;
    }

    public function getDocumentDirectory()
    {
        return $this->documentDirectory;
    }

    public function getExtendedDocumentDirectory()
    {
        return $this->extendedDocumentDirectory;
    }

    public function getDocumentMappingFiles()
    {
        try {
            $f = new Finder;
            $f->name('*.phpcr.xml.skeleton');
            $f->in($this->getMappingDocumentDirectory());

            return $f->getIterator();
        } catch (\Exception $e) {
            return array();
        }
    }

    public function getDocumentNames()
    {
        $names = array();

        try {
            $f = new Finder;
            $f->name('*.phpcr.xml.skeleton');
            $f->in($this->getMappingDocumentDirectory());

            foreach ($f->getIterator() as $file) {
                $name = explode('.', basename($file));
                $names[] = $name[0];
            }

        } catch (\Exception $e) {

        }

        return $names;
    }

    public function getRepositoryFiles()
    {
        try {
            $f = new Finder;
            $f->name('*Repository.php');
            $f->in($this->getDocumentDirectory());

            return $f->getIterator();
        } catch (\Exception $e) {
            return array();
        }
    }
}
