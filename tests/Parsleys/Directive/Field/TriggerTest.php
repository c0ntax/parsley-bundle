<?php

namespace C0ntax\ParsleyBundle\Tests\Parsleys\Directive\Field;

use C0ntax\ParsleyBundle\Parsleys\Directive\Field\Trigger;

class TriggerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider createGetViewAttrTestData
     */
    public function testGetViewAttr(string $value, array $expected)
    {
        $trigger = new Trigger($value);
        self::assertEquals($expected, $trigger->getViewAttr());
    }

    /**
     * @return array
     */
    public function createGetViewAttrTestData(): array
    {
        return [
            [
                'value' => 'click',
                'expected' => ['data-parsley-trigger' => 'click'],
            ],
            [
                'value' => 'change focus',
                'expected' => ['data-parsley-trigger' => 'change focus'],
            ],
        ];
    }
}
