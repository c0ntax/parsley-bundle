<?php

namespace C0ntax\ParsleyBundle\Tests\Directive\Field\Constraint;

use C0ntax\ParsleyBundle\Directive\Field\Constraint\Pattern;

class PatternTest extends \PHPUnit_Framework_TestCase
{

    public function testGetConstraintId()
    {
        self::assertEquals('pattern', Pattern::getConstraintId());
    }

    public function testGetViewAttr()
    {
        $pattern = new Pattern('/pattern/');
        self::assertEquals(['data-parsley-pattern' => '/pattern/'], $pattern->getViewAttr());

        $pattern = new Pattern('/pattern/', 'Error message');
        self::assertEquals(
            ['data-parsley-pattern' => '/pattern/', 'data-parsley-pattern-message' => 'Error message'],
            $pattern->getViewAttr()
        );
    }
}
