<?php

namespace C0ntax\ParsleyBundle\Tests\Factory;

use C0ntax\ParsleyBundle\Contracts\ConstraintInterface;
use C0ntax\ParsleyBundle\Directive\Field\Constraint\Pattern;
use C0ntax\ParsleyBundle\Factory\ConstraintFactory;
use C0ntax\ParsleyBundle\Form\Extension\ParsleyTypeExtension;
use C0ntax\ParsleyBundle\Tests\Fixtures\Form\Type\TestType;
use C0ntax\ParsleyBundle\Tests\Fixtures\Validator\Constraints\UnknownConstraint;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Forms;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\LessThan;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\Regex;
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
        ?ConstraintInterface $expected
    ) {
        $return = null;
        try {
            $return = ConstraintFactory::createFromValidationConstraint($constraint, $form);
        } catch (\RuntimeException $exception) {

        }
        self::assertEquals($expected, $return);
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
                new Regex(
                    [
                        'pattern' => '/pattern/',
                        'message' => 'This doesn\'t look right',
                    ]
                ),
                $form->get('id'),
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
                $form->get('id'),
                new \C0ntax\ParsleyBundle\Directive\Field\Constraint\Length(1, 20, 'This doesn\'t look right'),
            ],
            [
                new Length(
                    [
                        'min' => 1,
                        'minMessage' => 'This doesn\'t look right',
                    ]
                ),
                $form->get('id'),
                new \C0ntax\ParsleyBundle\Directive\Field\Constraint\MinLength(1, 'This doesn\'t look right'),
            ],
            [
                new Length(
                    [
                        'max' => 20,
                        'maxMessage' => 'This doesn\'t look right',
                    ]
                ),
                $form->get('id'),
                new \C0ntax\ParsleyBundle\Directive\Field\Constraint\MaxLength(20, 'This doesn\'t look right'),
            ],
            [
                new Email(
                    [
                        'message' => 'This doesn\'t look right',
                    ]
                ),
                $form->get('id'),
                new \C0ntax\ParsleyBundle\Directive\Field\Constraint\Email('This doesn\'t look right'),
            ],

            // Max 'n' Min (integer)

            [
                new GreaterThanOrEqual(['value' => 10, 'message' => 'Ouch']),
                $form->get('id'),
                new \C0ntax\ParsleyBundle\Directive\Field\Constraint\Min(10, 'Ouch'),
            ],
            [
                new GreaterThan(['value' => 10, 'message' => 'Ouch']),
                $form->get('id'),
                new \C0ntax\ParsleyBundle\Directive\Field\Constraint\Min(11, 'Ouch'),
            ],
            [
                new LessThanOrEqual(['value' => 10, 'message' => 'Ouch']),
                $form->get('id'),
                new \C0ntax\ParsleyBundle\Directive\Field\Constraint\Max(10, 'Ouch'),
            ],
            [
                new LessThan(['value' => 10, 'message' => 'Ouch']),
                $form->get('id'),
                new \C0ntax\ParsleyBundle\Directive\Field\Constraint\Max(9, 'Ouch'),
            ],

            // Max 'n' Min (float)

            [
                new GreaterThanOrEqual(['value' => 10.0, 'message' => 'Ouch']),
                $form->get('id'),
                new \C0ntax\ParsleyBundle\Directive\Field\Constraint\Min(10, 'Ouch'),
            ],
            [
                new GreaterThan(['value' => 10.0, 'message' => 'Ouch']),
                $form->get('id'),
                new \C0ntax\ParsleyBundle\Directive\Field\Constraint\Min(10.0000001, 'Ouch'),
            ],
            [
                new LessThanOrEqual(['value' => 10.0, 'message' => 'Ouch']),
                $form->get('id'),
                new \C0ntax\ParsleyBundle\Directive\Field\Constraint\Max(10, 'Ouch'),
            ],
            [
                new LessThan(['value' => 10.0, 'message' => 'Ouch']),
                $form->get('id'),
                new \C0ntax\ParsleyBundle\Directive\Field\Constraint\Max(9.9999999, 'Ouch'),
            ],

            // Max 'n' Min (DateTime) (The id field isn't a DateType so we shouldn't get a result back!)

            [
                new GreaterThanOrEqual(['value' => '2018-01-19', 'message' => 'Ouch']),
                $form->get('id'),
                null,
            ],
            [
                new GreaterThan(['value' => '2018-01-19', 'message' => 'Ouch']),
                $form->get('id'),
                null,
            ],
            [
                new LessThanOrEqual(['value' => '2018-01-19', 'message' => 'Ouch']),
                $form->get('id'),
                null,
            ],
            [
                new LessThan(['value' => '2018-01-19', 'message' => 'Ouch']),
                $form->get('id'),
                null,
            ],

            // Max 'n' Min (DateTime) (The date field is a DateType so we should get a result back!)

            [
                new GreaterThanOrEqual(['value' => '2018-01-19', 'message' => 'Ouch']),
                $form->get('date'),
                new \C0ntax\ParsleyBundle\Directive\Field\Constraint\Min('2018-01-19 00:00:00', 'Ouch'),
            ],
            [
                new GreaterThan(['value' => '2018-01-19', 'message' => 'Ouch']),
                $form->get('date'),
                new \C0ntax\ParsleyBundle\Directive\Field\Constraint\Min('2018-01-19 00:00:01', 'Ouch'),
            ],
            [
                new LessThanOrEqual(['value' => '2018-01-19', 'message' => 'Ouch']),
                $form->get('date'),
                new \C0ntax\ParsleyBundle\Directive\Field\Constraint\Max('2018-01-19 00:00:00', 'Ouch'),
            ],
            [
                new LessThan(['value' => '2018-01-19', 'message' => 'Ouch']),
                $form->get('date'),
                new \C0ntax\ParsleyBundle\Directive\Field\Constraint\Max('2018-01-18 23:59:59', 'Ouch'),
            ],

            // Max 'n' Min (DateTime) (The dob field is a BirthdayType so we shoul get a result back!)

            [
                new GreaterThanOrEqual(['value' => '2018-01-19', 'message' => 'Ouch']),
                $form->get('dob'),
                new \C0ntax\ParsleyBundle\Directive\Field\Constraint\Min('2018-01-19 00:00:00', 'Ouch'),
            ],
            [
                new GreaterThan(['value' => '2018-01-19', 'message' => 'Ouch']),
                $form->get('dob'),
                new \C0ntax\ParsleyBundle\Directive\Field\Constraint\Min('2018-01-19 00:00:01', 'Ouch'),
            ],
            [
                new LessThanOrEqual(['value' => '2018-01-19', 'message' => 'Ouch']),
                $form->get('dob'),
                new \C0ntax\ParsleyBundle\Directive\Field\Constraint\Max('2018-01-19 00:00:00', 'Ouch'),
            ],
            [
                new LessThan(['value' => '2018-01-19', 'message' => 'Ouch']),
                $form->get('dob'),
                new \C0ntax\ParsleyBundle\Directive\Field\Constraint\Max('2018-01-18 23:59:59', 'Ouch'),
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
