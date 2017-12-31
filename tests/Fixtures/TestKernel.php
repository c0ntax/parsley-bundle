<?php

namespace C0ntax\ParsleyBundle\Tests\Fixtures;

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
            new \Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new \C0ntax\ParsleyBundle\C0ntaxParsleyBundle(),
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
