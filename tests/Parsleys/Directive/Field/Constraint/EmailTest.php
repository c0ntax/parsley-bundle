<?php

namespace C0ntax\ParsleyBundle\Tests\Parsleys\Directive\Field\Constraint;

use C0ntax\ParsleyBundle\Parsleys\Directive\Field\Constraint\Email;

class EmailTest extends \PHPUnit_Framework_TestCase
{

    public function testGetConstraintId()
    {
        self::assertEquals('type', Email::getConstraintId());
    }

    public function testGetViewAttr()
    {
        $email = new Email();
        self::assertEquals(['data-parsley-type' => 'email'], $email->getViewAttr());

        $email = new Email('Error message');
        self::assertEquals(
            ['data-parsley-type' => 'email', 'data-parsley-type-message' => 'Error message'],
            $email->getViewAttr()
        );
    }
}
