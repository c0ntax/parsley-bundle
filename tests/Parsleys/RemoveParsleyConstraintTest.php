<?php

namespace C0ntax\ParsleyBundle\Tests\Parsleys;

use C0ntax\ParsleyBundle\Parsleys\Directive\Field\Constraint as ParsleyConstraint;
use C0ntax\ParsleyBundle\Parsleys\Directive\Field\ConstraintErrorMessage;
use C0ntax\ParsleyBundle\Parsleys\Directive\Field\Generic;
use C0ntax\ParsleyBundle\Parsleys\RemoveParsleyConstraint;
use Symfony\Component\Validator\Constraints as SymfonyConstraint;

class RemoveParsleyConstraintTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getValidClassNames
     * @param string $className
     */
    public function testValid(string $className)
    {
        self::assertInstanceOf(RemoveParsleyConstraint::class, new RemoveParsleyConstraint($className));
    }

    /**
     * @dataProvider getInvalidClassNames
     * @param string $className
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessageRegExp /^Class [\S]+ does not implement C0ntax\\ParsleyBundle\\Contracts\\ConstraintInterface$/
     */
    public function testInvalid(string $className)
    {
        new RemoveParsleyConstraint($className);
    }

    public function getValidClassNames()
    {
        return [
            [
                'className' => ParsleyConstraint\Email::class,
            ],
            [
                'className' => ParsleyConstraint\Length::class,
            ],
            [
                'className' => ParsleyConstraint\Max::class,
            ],
            [
                'className' => ParsleyConstraint\MaxLength::class,
            ],
            [
                'className' => ParsleyConstraint\Min::class,
            ],
            [
                'className' => ParsleyConstraint\MinLength::class,
            ],
            [
                'className' => ParsleyConstraint\Pattern::class,
            ],
            [
                'className' => ParsleyConstraint\Range::class,
            ],
            [
                'className' => ParsleyConstraint\Required::class,
            ],
        ];
    }

    public function getInvalidClassNames()
    {
        return [
            [
                'className' => ConstraintErrorMessage::class,
            ],
            [
                'className' => Generic::class,
            ],
            [
                'className' => SymfonyConstraint\Email::class,
            ],
            [
                'className' => SymfonyConstraint\Length::class,
            ],
            [
                'className' => SymfonyConstraint\Regex::class,
            ],
        ];
    }
}
