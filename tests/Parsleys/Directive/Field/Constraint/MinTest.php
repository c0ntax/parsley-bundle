<?php

namespace C0ntax\ParsleyBundle\Tests\Parsleys\Directive\Field\Constraint;

use C0ntax\ParsleyBundle\Parsleys\Directive\Field\Constraint\Min;

class MinTest extends \PHPUnit_Framework_TestCase
{
    public function testGetConstraintId()
    {
        self::assertEquals('min', Min::getConstraintId());
    }

    public function testGetViewAttr()
    {
        $value = new Min(20);
        self::assertEquals(['data-parsley-min' => '20'], $value->getViewAttr());

        $value = new Min(20, 'Error message');
        self::assertEquals(
            ['data-parsley-min' => '20', 'data-parsley-min-message' => 'Error message'],
            $value->getViewAttr()
        );

        $timeNow = new \DateTime('now');

        $value = new Min($timeNow);
        self::assertEquals(
            ['data-parsley-min' => $timeNow->format('Y-m-d H:i:s')],
            $value->getViewAttr()
        );
    }
}
