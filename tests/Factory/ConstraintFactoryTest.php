<?php

namespace C0ntax\ParsleyBundle\Tests\Factory;

use C0ntax\ParsleyBundle\Contracts\ConstraintInterface;
use C0ntax\ParsleyBundle\Directive\Field\Constraint\Pattern;
use C0ntax\ParsleyBundle\Factory\ConstraintFactory;
use C0ntax\ParsleyBundle\Tests\Fixtures\Validator\Constraints\UnknownConstraint;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\LessThan;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
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

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Unsupported Symfony Constraint: C0ntax\ParsleyBundle\Tests\Fixtures\Validator\Constraints\UnknownConstraint
     */
    public function testCreateFromValidationConstraintException()
    {
        ConstraintFactory::createFromValidationConstraint(new UnknownConstraint());
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
                new \C0ntax\ParsleyBundle\Directive\Field\Constraint\Length(1, 20, 'This doesn\'t look right'),
            ],
            [
                new Length(
                    [
                        'min' => 1,
                        'minMessage' => 'This doesn\'t look right',
                    ]
                ),
                new \C0ntax\ParsleyBundle\Directive\Field\Constraint\MinLength(1, 'This doesn\'t look right'),
            ],
            [
                new Length(
                    [
                        'max' => 20,
                        'maxMessage' => 'This doesn\'t look right',
                    ]
                ),
                new \C0ntax\ParsleyBundle\Directive\Field\Constraint\MaxLength(20, 'This doesn\'t look right'),
            ],
            [
                new Email(
                    [
                        'message' => 'This doesn\'t look right',
                    ]
                ),
                new \C0ntax\ParsleyBundle\Directive\Field\Constraint\Email('This doesn\'t look right'),
            ],

            // Max 'n' Min (integer)

            [
                new GreaterThanOrEqual(['value' => 10, 'message' => 'Ouch']),
                new \C0ntax\ParsleyBundle\Directive\Field\Constraint\Min(10, 'Ouch'),
            ],
            [
                new GreaterThan(['value' => 10, 'message' => 'Ouch']),
                new \C0ntax\ParsleyBundle\Directive\Field\Constraint\Min(11, 'Ouch'),
            ],
            [
                new LessThanOrEqual(['value' => 10, 'message' => 'Ouch']),
                new \C0ntax\ParsleyBundle\Directive\Field\Constraint\Max(10, 'Ouch'),
            ],
            [
                new LessThan(['value' => 10, 'message' => 'Ouch']),
                new \C0ntax\ParsleyBundle\Directive\Field\Constraint\Max(9, 'Ouch'),
            ],

            // Max 'n' Min (float)

            [
                new GreaterThanOrEqual(['value' => 10.0, 'message' => 'Ouch']),
                new \C0ntax\ParsleyBundle\Directive\Field\Constraint\Min(10, 'Ouch'),
            ],
            [
                new GreaterThan(['value' => 10.0, 'message' => 'Ouch']),
                new \C0ntax\ParsleyBundle\Directive\Field\Constraint\Min(10.0000001, 'Ouch'),
            ],
            [
                new LessThanOrEqual(['value' => 10.0, 'message' => 'Ouch']),
                new \C0ntax\ParsleyBundle\Directive\Field\Constraint\Max(10, 'Ouch'),
            ],
            [
                new LessThan(['value' => 10.0, 'message' => 'Ouch']),
                new \C0ntax\ParsleyBundle\Directive\Field\Constraint\Max(9.9999999, 'Ouch'),
            ],

            // Max 'n' Min (DateTime)

            [
                new GreaterThanOrEqual(['value' => '2018-01-19', 'message' => 'Ouch']),
                new \C0ntax\ParsleyBundle\Directive\Field\Constraint\Min('2018-01-19 00:00:00', 'Ouch'),
            ],
            [
                new GreaterThan(['value' => '2018-01-19', 'message' => 'Ouch']),
                new \C0ntax\ParsleyBundle\Directive\Field\Constraint\Min('2018-01-19 00:00:01', 'Ouch'),
            ],
            [
                new LessThanOrEqual(['value' => '2018-01-19', 'message' => 'Ouch']),
                new \C0ntax\ParsleyBundle\Directive\Field\Constraint\Max('2018-01-19 00:00:00', 'Ouch'),
            ],
            [
                new LessThan(['value' => '2018-01-19', 'message' => 'Ouch']),
                new \C0ntax\ParsleyBundle\Directive\Field\Constraint\Max('2018-01-18 23:59:59', 'Ouch'),
            ],
        ];
    }
}
