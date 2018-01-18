<?php

namespace C0ntax\ParsleyBundle\Tests\Directive\Field;

use C0ntax\ParsleyBundle\Directive\Field\Constraint\Pattern;
use C0ntax\ParsleyBundle\Directive\Field\ConstraintErrorMessage;

class ConstraintErrorMessageTest extends \PHPUnit_Framework_TestCase
{
    public function testGetViewAttr()
    {
        $constraintErrorMessage = new ConstraintErrorMessage(Pattern::class, 'Error message');
        self::assertEquals(
            ['data-parsley-pattern-message' => 'Error message'],
            $constraintErrorMessage->getViewAttr()
        );
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage not a class is not a class and therefore doesn't implement C0ntax\ParsleyBundle\Contracts\ConstraintInterface
     */
    public function testGetViewAttrFail1()
    {
        $constraintErrorMessage = new ConstraintErrorMessage('not a class', 'Error message');
        self::assertEquals(
            ['data-parsley-pattern-message' => 'Error message'],
            $constraintErrorMessage->getViewAttr()
        );
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Class C0ntax\ParsleyBundle\Tests\Directive\Field\ConstraintErrorMessageTest does not implement C0ntax\ParsleyBundle\Contracts\ConstraintInterface
     */
    public function testGetViewAttrFail2()
    {
        $constraintErrorMessage = new ConstraintErrorMessage(__CLASS__, 'Error message');
        self::assertEquals(
            ['data-parsley-pattern-message' => 'Error message'],
            $constraintErrorMessage->getViewAttr()
        );
    }
}
