<?php

namespace C0ntax\ParsleyBundle\Tests\Parsleys\Directive\Field\Constraint;

use C0ntax\ParsleyBundle\Parsleys\Directive\Field\Constraint\Max;

class MaxTest extends \PHPUnit_Framework_TestCase
{
    public function testGetConstraintId()
    {
        self::assertEquals('max', Max::getConstraintId());
    }

    public function testGetViewAttr()
    {
        $value = new Max(20);
        self::assertEquals(['data-parsley-max' => '20'], $value->getViewAttr());

        $value = new Max(20, 'Error message');
        self::assertEquals(
            ['data-parsley-max' => '20', 'data-parsley-max-message' => 'Error message'],
            $value->getViewAttr()
        );

        $timeNow = new \DateTime('now');

        $value = new Max($timeNow);
        self::assertEquals(
            ['data-parsley-max' => $timeNow->format('Y-m-d H:i:s')],
            $value->getViewAttr()
        );
    }
}
