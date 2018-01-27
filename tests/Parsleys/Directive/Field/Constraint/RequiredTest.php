<?php

namespace C0ntax\ParsleyBundle\Tests\Parsleys\Directive\Field\Constraint;

use C0ntax\ParsleyBundle\Parsleys\Directive\Field\Constraint\Required;

class RequiredTest extends \PHPUnit_Framework_TestCase
{

    public function testGetConstraintId()
    {
        self::assertEquals('required', Required::getConstraintId());
    }

    public function testGetViewAttr()
    {
        $pattern = new Required();
        self::assertEquals(['data-parsley-required' => 'true'], $pattern->getViewAttr());

        $pattern = new Required('Error message');
        self::assertEquals(
            ['data-parsley-required' => 'true', 'data-parsley-required-message' => 'Error message'],
            $pattern->getViewAttr()
        );
    }
}
