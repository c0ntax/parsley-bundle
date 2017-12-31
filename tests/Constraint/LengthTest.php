<?php

namespace C0ntax\ParsleyBundle\Tests\Constraint;

use C0ntax\ParsleyBundle\Constraint\Length;

class LengthTest extends \PHPUnit_Framework_TestCase
{
    public function testGetConstraintId()
    {
        self::assertEquals('length', Length::getConstraintId());
    }

    public function testGetViewAttr()
    {
        $pattern = new Length(1, 20);
        self::assertEquals(['data-parsley-length' => '[1, 20]'], $pattern->getViewAttr());

        $pattern = new Length(1, 20, 'Error message');
        self::assertEquals(
            ['data-parsley-length' => '[1, 20]', 'data-parsley-length-message' => 'Error message'],
            $pattern->getViewAttr()
        );
    }
}
