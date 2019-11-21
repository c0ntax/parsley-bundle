<?php

namespace C0ntax\ParsleyBundle\Tests\Parsleys;

use C0ntax\ParsleyBundle\Parsleys\Directive\Field\Constraint as ParsleyConstraint;
use C0ntax\ParsleyBundle\Parsleys\Directive\Field\ConstraintErrorMessage;
use C0ntax\ParsleyBundle\Parsleys\Directive\Field\Generic;
use C0ntax\ParsleyBundle\Parsleys\Directive\Field\Trigger;
use C0ntax\ParsleyBundle\Parsleys\RemoveParsleyDirective;
use Symfony\Component\Validator\Constraints as SymfonyConstraint;

class RemoveParsleyDirectiveTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getValidClassNames
     *
     * @param string $className
     */
    public function testValid(string $className)
    {
        self::assertInstanceOf(RemoveParsleyDirective::class, new RemoveParsleyDirective($className));
    }

    /**
     * @dataProvider getInvalidClassNames
     *
     * @param string $className
     *
     * @expectedException \InvalidArgumentException
     *
     * @expectedExceptionMessageRegExp /^Class [\S]+ does not implement C0ntax\\ParsleyBundle\\Contracts\\DirectiveInterface$/
     */
    public function testInvalid(string $className)
    {
        new RemoveParsleyDirective($className);
    }

    public function getValidClassNames()
    {
        return [
            [
                'className' => ConstraintErrorMessage::class,
            ],
            [
                'className' => Generic::class,
            ],
            [
                'className' => Trigger::class,
            ],
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
