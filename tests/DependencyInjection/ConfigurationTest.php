<?php

namespace C0ntax\ParsleyBundle\Tests\DependencyInjection;

use C0ntax\ParsleyBundle\DependencyInjection\Configuration;
use Matthias\SymfonyConfigTest\PhpUnit\ConfigurationTestCaseTrait;

class ConfigurationTest extends \PHPUnit_Framework_TestCase
{
    use ConfigurationTestCaseTrait;

    public function testEmpty()
    {
        $config = [];
        $this->assertConfigurationIsValid($config);
        $this->assertProcessedConfigurationEquals($config, ['enabled' => false, 'field' => ['trigger' => null]]);
    }

    public function testEnabled()
    {

        $config = [['enabled' => true]];
        $this->assertConfigurationIsValid($config);
        $this->assertProcessedConfigurationEquals($config, ['enabled' => true, 'field' => ['trigger' => null]]);
    }

    public function testFieldEmpty()
    {

        $config = [['enabled' => true, 'field' => []]];
        $this->assertConfigurationIsValid($config);
        $this->assertProcessedConfigurationEquals($config, ['enabled' => true, 'field' => ['trigger' => null]]);
    }

    public function testFieldTriggerNull()
    {

        $config = [['enabled' => true, 'field' => ['trigger' => null]]];
        $this->assertConfigurationIsValid($config);
        $this->assertProcessedConfigurationEquals($config, ['enabled' => true, 'field' => ['trigger' => null]]);
    }

    public function testFieldTriggerSet()
    {

        $config = [['enabled' => true, 'field' => ['trigger' => 'focusout']]];
        $this->assertConfigurationIsValid($config);
        $this->assertProcessedConfigurationEquals($config, ['enabled' => true, 'field' => ['trigger' => 'focusout']]);
    }

    protected function getConfiguration()
    {
        return new Configuration();
    }
}
