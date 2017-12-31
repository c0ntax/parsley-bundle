<?php

namespace C0ntax\ParsleyBundle\Tests\Constraint;

use C0ntax\ParsleyBundle\Constraint\MinLength;

class MinLengthTest extends \PHPUnit_Framework_TestCase
{
    public function testGetConstraintId()
    {
        self::assertEquals('minlength', MinLength::getConstraintId());
    }

    public function testGetViewAttr()
    {
        $pattern = new MinLength(20);
        self::assertEquals(['data-parsley-minlength' => '20'], $pattern->getViewAttr());

        $pattern = new MinLength(20, 'Error message');
        self::assertEquals(
            ['data-parsley-minlength' => '20', 'data-parsley-minlength-message' => 'Error message'],
            $pattern->getViewAttr()
        );
    }
}
