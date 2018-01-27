<?php

namespace C0ntax\ParsleyBundle\Tests\Factory;

use C0ntax\ParsleyBundle\Contracts\ConstraintInterface;
use C0ntax\ParsleyBundle\Factory\ConstraintFactory;
use C0ntax\ParsleyBundle\Form\Extension\ParsleyTypeExtension;
use C0ntax\ParsleyBundle\Parsleys\Directive\Field\Constraint as ParsleyConstraint;
use C0ntax\ParsleyBundle\Tests\Fixtures\Form\Type\TestType;
use C0ntax\ParsleyBundle\Tests\Fixtures\Validator\Constraints\UnknownConstraint;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Forms;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints as SymfonyConstriant;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ConstraintFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getFactoryTestData
     */
    public function testCreateFromValidationConstraint(
        Constraint $constraint,
        FormInterface $form,
        ?ConstraintInterface $expected,
        string $testName
    ) {
        $return = null;
        try {
            $return = ConstraintFactory::createFromValidationConstraint($constraint, $form);
        } catch (\RuntimeException $exception) {
        }
        self::assertEquals($expected, $return, $testName);
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Unsupported Symfony Constraint: C0ntax\ParsleyBundle\Tests\Fixtures\Validator\Constraints\UnknownConstraint
     */
    public function testCreateFromValidationConstraintException()
    {
        ConstraintFactory::createFromValidationConstraint(
            new UnknownConstraint(),
            $this->getMockBuilder(FormInterface::class)->disableOriginalConstructor()->getMock()
        );
    }

    public function getFactoryTestData()
    {
        $factory = $this->getFormFactoryBuilder();
        $form = $factory->create(TestType::class, null, []);

        return [
            [
                new SymfonyConstriant\Valid(),
                $form->get('id'),
                null,
                'Valid to Null',
            ],
            [
                new SymfonyConstriant\NotNull(['message' => 'Give me something']),
                $form->get('id'),
                new ParsleyConstraint\Required('Give me something'),
                'NotNull to Required',
            ],
            [
                new SymfonyConstriant\NotBlank(['message' => 'Give me something']),
                $form->get('id'),
                new ParsleyConstraint\Required('Give me something'),
                'NotBlank to Required',
            ],
            [
                new SymfonyConstriant\Regex(
                    [
                        'pattern' => '/pattern/',
                        'message' => 'This doesn\'t look right',
                    ]
                ),
                $form->get('id'),
                new ParsleyConstraint\Pattern('/pattern/', 'This doesn\'t look right'),
                'Regex to Pattern',
            ],
            [
                new SymfonyConstriant\Length(
                    [
                        'min' => 1,
                        'max' => 20,
                        'exactMessage' => 'This doesn\'t look right',
                    ]
                ),
                $form->get('id'),
                new ParsleyConstraint\Length(1, 20, 'This doesn\'t look right'),
                'Length to Length',
            ],
            [
                new SymfonyConstriant\Length(
                    [
                        'min' => 1,
                        'minMessage' => 'This doesn\'t look right',
                    ]
                ),
                $form->get('id'),
                new ParsleyConstraint\MinLength(1, 'This doesn\'t look right'),
                'Length to MinLength',
            ],
            [
                new SymfonyConstriant\Length(
                    [
                        'max' => 20,
                        'maxMessage' => 'This doesn\'t look right',
                    ]
                ),
                $form->get('id'),
                new ParsleyConstraint\MaxLength(20, 'This doesn\'t look right'),
                'Length to MaxLength',
            ],
            [
                new SymfonyConstriant\Email(
                    [
                        'message' => 'This doesn\'t look right',
                    ]
                ),
                $form->get('id'),
                new ParsleyConstraint\Email('This doesn\'t look right'),
                'Email to Email',
            ],

            // Max 'n' Min (integer)

            [
                new SymfonyConstriant\GreaterThanOrEqual(['value' => 10, 'message' => 'Ouch']),
                $form->get('id'),
                new ParsleyConstraint\Min(10, 'Ouch'),
                'GreaterThanOrEqual to Min (integer)',
            ],
            [
                new SymfonyConstriant\GreaterThan(['value' => 10, 'message' => 'Ouch']),
                $form->get('id'),
                new ParsleyConstraint\Min(11, 'Ouch'),
                'GreaterThan to Min (integer)',
            ],
            [
                new SymfonyConstriant\LessThanOrEqual(['value' => 10, 'message' => 'Ouch']),
                $form->get('id'),
                new ParsleyConstraint\Max(10, 'Ouch'),
                'LessThanOrEqual to Max (integer)',
            ],
            [
                new SymfonyConstriant\LessThan(['value' => 10, 'message' => 'Ouch']),
                $form->get('id'),
                new ParsleyConstraint\Max(9, 'Ouch'),
                'LessThan to Max (integer)',
            ],

            // Max 'n' Min (float)

            [
                new SymfonyConstriant\GreaterThanOrEqual(['value' => 10.0, 'message' => 'Ouch']),
                $form->get('id'),
                new ParsleyConstraint\Min(10, 'Ouch'),
                'GreaterThanOrEqual to Min (float)',
            ],
            [
                new SymfonyConstriant\GreaterThan(['value' => 10.0, 'message' => 'Ouch']),
                $form->get('id'),
                new ParsleyConstraint\Min(10.0000001, 'Ouch'),
                'GreaterThan to Min (float)',
            ],
            [
                new SymfonyConstriant\LessThanOrEqual(['value' => 10.0, 'message' => 'Ouch']),
                $form->get('id'),
                new ParsleyConstraint\Max(10, 'Ouch'),
                'LessThanOrEqual to Max (float)',
            ],
            [
                new SymfonyConstriant\LessThan(['value' => 10.0, 'message' => 'Ouch']),
                $form->get('id'),
                new ParsleyConstraint\Max(9.9999999, 'Ouch'),
                'LessThanOrEqual to Max (float)',
            ],

            // Max 'n' Min (DateTime) (The id field isn't a DateType so we shouldn't get a result back!)

            [
                new SymfonyConstriant\GreaterThanOrEqual(['value' => '2018-01-19', 'message' => 'Ouch']),
                $form->get('id'),
                null,
                'GreaterThanOrEqual to Null (date (non-date field))',
            ],
            [
                new SymfonyConstriant\GreaterThan(['value' => '2018-01-19', 'message' => 'Ouch']),
                $form->get('id'),
                null,
                'GreaterThan to Null (date (non-date field))',
            ],
            [
                new SymfonyConstriant\LessThanOrEqual(['value' => '2018-01-19', 'message' => 'Ouch']),
                $form->get('id'),
                null,
                'LessThanOrEqual to Null (date (non-date field))',
            ],
            [
                new SymfonyConstriant\LessThan(['value' => '2018-01-19', 'message' => 'Ouch']),
                $form->get('id'),
                null,
                'LessThan to Null (date (non-date field))',
            ],

            // Max 'n' Min (DateTime) (The date field is a DateType so we should get a result back!)

            [
                new SymfonyConstriant\GreaterThanOrEqual(['value' => '2018-01-19', 'message' => 'Ouch']),
                $form->get('date'),
                new ParsleyConstraint\Min('2018-01-19 00:00:00', 'Ouch'),
                'GreaterThanOrEqual to Min (date (date field))',
            ],
            [
                new SymfonyConstriant\GreaterThan(['value' => '2018-01-19', 'message' => 'Ouch']),
                $form->get('date'),
                new ParsleyConstraint\Min('2018-01-19 00:00:01', 'Ouch'),
                'GreaterThan to Min (date (date field))',
            ],
            [
                new SymfonyConstriant\LessThanOrEqual(['value' => '2018-01-19', 'message' => 'Ouch']),
                $form->get('date'),
                new ParsleyConstraint\Max('2018-01-19 00:00:00', 'Ouch'),
                'LessThanOrEqual to Max (date (date field))',
            ],
            [
                new SymfonyConstriant\LessThan(['value' => '2018-01-19', 'message' => 'Ouch']),
                $form->get('date'),
                new ParsleyConstraint\Max('2018-01-18 23:59:59', 'Ouch'),
                'LessThan to Max (date (date field))',
            ],

            // Max 'n' Min (DateTime) (The dob field is a BirthdayType so we shoul get a result back!)

            [
                new SymfonyConstriant\GreaterThanOrEqual(['value' => '2018-01-19', 'message' => 'Ouch']),
                $form->get('dob'),
                new ParsleyConstraint\Min('2018-01-19 00:00:00', 'Ouch'),
                'GreaterThanOrEqual to Min (date (birthday field))',
            ],
            [
                new SymfonyConstriant\GreaterThan(['value' => '2018-01-19', 'message' => 'Ouch']),
                $form->get('dob'),
                new ParsleyConstraint\Min('2018-01-19 00:00:01', 'Ouch'),
                'GreaterThan to Min (date (birthday field))',
            ],
            [
                new SymfonyConstriant\LessThanOrEqual(['value' => '2018-01-19', 'message' => 'Ouch']),
                $form->get('dob'),
                new ParsleyConstraint\Max('2018-01-19 00:00:00', 'Ouch'),
                'LessThanOrEqual to Min (date (birthday field))',
            ],
            [
                new SymfonyConstriant\LessThan(['value' => '2018-01-19', 'message' => 'Ouch']),
                $form->get('dob'),
                new ParsleyConstraint\Max('2018-01-18 23:59:59', 'Ouch'),
                'LessThan to Min (date (birthday field))',
            ],

            // Max 'n' Min (DateTime) (The dateNotHtml field is a DateType but not HTML5 so we should get null back)

            [
                new SymfonyConstriant\GreaterThanOrEqual(['value' => '2018-01-19', 'message' => 'Ouch']),
                $form->get('dateNotHtml5'),
                null,
                'GreaterThanOrEqual to Null (date (non-html5-date field))',
            ],
            [
                new SymfonyConstriant\GreaterThan(['value' => '2018-01-19', 'message' => 'Ouch']),
                $form->get('dateNotHtml5'),
                null,
                'GreaterThan to Null (date (non-html5-date field))',
            ],
            [
                new SymfonyConstriant\LessThanOrEqual(['value' => '2018-01-19', 'message' => 'Ouch']),
                $form->get('dateNotHtml5'),
                null,
                'LessThanOrEqual to Null (date (non-html5-date field))',
            ],
            [
                new SymfonyConstriant\LessThan(['value' => '2018-01-19', 'message' => 'Ouch']),
                $form->get('dateNotHtml5'),
                null,
                'LessThan to Null (date (non-html5-date field))',
            ],

            // Range - Numeric

            [
                new SymfonyConstriant\Range(['min' => 1, 'max' => 10, 'minMessage' => 'Too small', 'maxMessage' => 'Too big']),
                $form->get('id'),
                new ParsleyConstraint\Range(1, 10),
                'Range to Range (numeric)',
            ],

            // Range - DateTime
            [
                new SymfonyConstriant\Range(['min' => '2018-01-25', 'max' => '2018-01-25', 'minMessage' => 'Too small', 'maxMessage' => 'Too big']),
                $form->get('id'),
                null,
                'Range to Range (date invalid)',
            ],

            [
                new SymfonyConstriant\Range(['min' => '2018-01-25', 'max' => '2018-01-26', 'minMessage' => 'Too small', 'maxMessage' => 'Too big']),
                $form->get('date'),
                new ParsleyConstraint\Range('2018-01-25 00:00:00', '2018-01-26 00:00:00'),
                'Range to Range (date valid)',
            ],

        ];
    }

    /**
     * @return \Symfony\Component\Form\FormFactoryInterface
     */
    private function getFormFactoryBuilder()
    {
        $validator = $this->getMockBuilder(ValidatorInterface::class)->disableOriginalConstructor()->getMock();
        $validator
            ->method('validate')
            ->willReturn(new ConstraintViolationList());

        $validator
            ->method('getMetadataFor')
            ->willReturn(new ClassMetadata(Form::class));

        return Forms::createFormFactoryBuilder()
            ->addExtensions([new ValidatorExtension($validator)])
            ->addTypeExtensions([new ParsleyTypeExtension(['enabled' => true], $validator)])
            ->addTypes([])
            ->addTypeGuessers([])
            ->getFormFactory();
    }
}
