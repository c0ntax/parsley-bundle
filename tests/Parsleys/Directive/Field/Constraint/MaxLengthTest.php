<?php

namespace C0ntax\ParsleyBundle\Tests\Parsleys\Directive\Field\Constraint;

use C0ntax\ParsleyBundle\Parsleys\Directive\Field\Constraint\MaxLength;

class MaxLengthTest extends \PHPUnit_Framework_TestCase
{
    public function testGetConstraintId()
    {
        self::assertEquals('maxlength', MaxLength::getConstraintId());
    }

    public function testGetViewAttr()
    {
        $pattern = new MaxLength(20);
        self::assertEquals(['data-parsley-maxlength' => '20'], $pattern->getViewAttr());

        $pattern = new MaxLength(20, 'Error message');
        self::assertEquals(
            ['data-parsley-maxlength' => '20', 'data-parsley-maxlength-message' => 'Error message'],
            $pattern->getViewAttr()
        );
    }
}
