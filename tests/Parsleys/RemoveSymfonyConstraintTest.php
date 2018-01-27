<?php

namespace C0ntax\ParsleyBundle\Tests\Parsleys;

use C0ntax\ParsleyBundle\Parsleys\Directive\Field\Constraint as ParsleyConstraint;
use C0ntax\ParsleyBundle\Parsleys\RemoveSymfonyConstraint;
use Symfony\Component\Validator\Constraints as SymfonyConstraint;

class RemoveSymfonyConstraintTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getValidClassNames
     * @param string $className
     */
    public function testValid(string $className)
    {
        self::assertInstanceOf(RemoveSymfonyConstraint::class, new RemoveSymfonyConstraint($className));
    }

    /**
     * @dataProvider getInvalidClassNames
     * @param string $className
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessageRegExp /^Class [\S]+ does not implement Symfony\\Component\\Validator\\Constraint$/
     */
    public function testInvalid(string $className)
    {
        new RemoveSymfonyConstraint($className);
    }

    public function getValidClassNames()
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

    public function getInValidClassNames()
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
}
