<?php

namespace C0ntax\ParsleyBundle\Tests\Directive\Field\Constraint;

use C0ntax\ParsleyBundle\Directive\Field\Constraint\Range;

class RangeTest extends \PHPUnit_Framework_TestCase
{

    public function testGetConstraintId()
    {
        self::assertEquals('range', Range::getConstraintId());
    }

    public function testGetViewAttr()
    {
        $pattern = new Range(1, 10);
        self::assertEquals(['data-parsley-range' => '["1", "10"]'], $pattern->getViewAttr());

        $pattern = new Range(1, 10, 'Error message');
        self::assertEquals(['data-parsley-range' => '["1", "10"]', 'data-parsley-range-message' => 'Error message'], $pattern->getViewAttr());

        $minDate = (new \DateTime('now'))->sub(new \DateInterval('P2W'));
        $maxDate = (new \DateTime('now'))->add(new \DateInterval('P2W'));

        $minDateString = $minDate->format('Y-m-d H:i:s');
        $maxDateString = $maxDate->format('Y-m-d H:i:s');

        $pattern = new Range($minDate, $maxDate, 'Error message');
        self::assertEquals(['data-parsley-range' => '["'.$minDateString.'", "'.$maxDateString.'"]', 'data-parsley-range-message' => 'Error message'], $pattern->getViewAttr());
    }
}
