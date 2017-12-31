<?php

namespace C0ntax\ParsleyBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class C0ntaxParsleyBundleTest extends KernelTestCase
{
    public function setUp()
    {
        parent::setUp();
        static::bootKernel();
    }

    public function testServiceDefined()
    {
        self::assertTrue(static::$kernel->getContainer()->has('c0ntax_parsley.form.extension.parsley'));
    }
}
