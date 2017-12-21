<?php

namespace C0ntax\ParsleyBundle\Tests\Factory;

use C0ntaX\ParsleyBundle\Constraint\Pattern;
use C0ntaX\ParsleyBundle\Contracts\ConstraintInterface;
use C0ntaX\ParsleyBundle\Factory\ConstraintFactory;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;

class ConstraintFactoryTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider getFactoryTestData
     */
    public function testCreateFromValidationConstraint(Constraint $constraint, ConstraintInterface $expected)
    {
        self::assertEquals($expected, ConstraintFactory::createFromValidationConstraint($constraint));
    }

    public function getFactoryTestData()
    {
        return [
            [
                new Regex(
                    [
                        'pattern' => '/pattern/',
                        'message' => 'This doesn\'t look right',
                    ]
                ),
                new Pattern('/pattern/', 'This doesn\'t look right'),
            ],
            [
                new Length(
                    [
                        'min' => 1,
                        'max' => 20,
                        'exactMessage' => 'This doesn\'t look right',
                    ]
                ),
                new \C0ntaX\ParsleyBundle\Constraint\Length(1, 20, 'This doesn\'t look right'),
            ],
            [
                new Length(
                    [
                        'min' => 1,
                        'minMessage' => 'This doesn\'t look right',
                    ]
                ),
                new \C0ntaX\ParsleyBundle\Constraint\MinLength(1, 'This doesn\'t look right'),
            ],
            [
                new Length(
                    [
                        'max' => 20,
                        'maxMessage' => 'This doesn\'t look right',
                    ]
                ),
                new \C0ntaX\ParsleyBundle\Constraint\MaxLength(20, 'This doesn\'t look right'),
            ],
            [
                new Email(
                    [
                        'message' => 'This doesn\'t look right',
                    ]
                ),
                new \C0ntaX\ParsleyBundle\Constraint\Email('This doesn\'t look right'),
            ],
        ];
    }
}
