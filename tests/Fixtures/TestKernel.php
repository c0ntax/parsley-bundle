<?php

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

/**
 * Used for functional tests.
 */
class TestKernel extends Kernel
{
    public function registerBundles()
    {
        return [
            new \C0ntax\ParsleyBundle\C0ntaxParsleyBundle(),
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
        ];
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/Resources/config/config.yml');
        $loader->load(__DIR__.'/../../src/Resources/config/services.yml');
    }

    public function getCacheDir()
    {
        return $this->rootDir.'/cache/'.$this->environment;
    }
}
