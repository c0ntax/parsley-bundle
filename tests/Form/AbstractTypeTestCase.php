<?php

namespace C0ntax\ParsleyBundle\Tests\Form;

use C0ntax\ParsleyBundle\Form\Extension\ParsleyTypeExtension;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ValidatorBuilder;

class AbstractTypeTestCase extends TypeTestCase
{
    /** @var ValidatorInterface|\PHPUnit_Framework_MockObject_MockObject */
    private $validator;

    protected function setUp()
    {
        $validatorBuilder = new ValidatorBuilder();
        $validatorBuilder->enableAnnotationMapping(new AnnotationReader());
        $validator = $validatorBuilder->getValidator();

        $this->setValidator($validator);

//        $this->setValidator($this->getMockBuilder(ValidatorInterface::class)->getMock());
//
//        $this->getValidator()
//            ->method('validate')
//            ->willReturn(new ConstraintViolationList());
//
//        $this->getValidator()
//            ->method('getMetadataFor')
//            ->willReturn(new ClassMetadata(Form::class));
//
        parent::setUp();
    }

    protected function tearDown()
    {
        parent::tearDown();

        $this->setValidator(null);

        // Flush out all data from the test
        $refl = new \ReflectionObject($this);
        foreach ($refl->getProperties() as $prop) {
            if (!$prop->isStatic() && 0 !== strpos($prop->getDeclaringClass()->getName(), 'PHPUnit_')) {
                $prop->setAccessible(true);
                $prop->setValue($this, null);
            }
        }
        gc_collect_cycles();
    }

    /**
     * @return array
     */
    protected function getExtensions()
    {
        return array_merge(
            parent::getExtensions(),
            [
                new ValidatorExtension($this->getValidator()),
            ]
        );
    }

    /**
     * @return array
     */
    public function getTypeExtensions(): array
    {
        return [
            new ParsleyTypeExtension($this->getParsleyTypeConfig(), $this->getValidator()),
        ];
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|ValidatorInterface
     */
    protected function getValidator()
    {
        return $this->validator;
    }

    /**
     * @param \PHPUnit_Framework_MockObject_MockObject|ValidatorInterface $validator
     *
     * @return AbstractTypeTestCase
     */
    protected function setValidator($validator): AbstractTypeTestCase
    {
        $this->validator = $validator;

        return $this;
    }
}
