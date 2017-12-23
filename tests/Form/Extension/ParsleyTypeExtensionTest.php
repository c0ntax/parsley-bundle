<?php

namespace C0ntax\ParsleyBundle\Tests\Form\Extension;

use C0ntaX\ParsleyBundle\Tests\Fixtures\Form\Type\TestType;
use C0ntaX\ParsleyBundle\Tests\Form\AbstractTypeTestCase;
use Symfony\Component\Form\FormInterface;

class ParsleyTypeExtensionTest extends AbstractTypeTestCase
{
    public function testSomething()
    {
        $form = $this->createForm();
        $form->submit(['id' => 9, 'email' => 'sausage', 'string' => str_repeat('a', 51)]);

        $view = $form->createView();

        self::assertEquals(
            [
                'data-parsley-trigger' => 'focusout',
                'data-parsley-minlength' => '2',
                'data-parsley-pattern-message' => 'This value is not valid.',
                'data-parsley-pattern' => '/[e]+/',
                'data-parsley-maxlength-message' => 'You need less than %s chars',
                'data-parsley-maxlength' => '10',
                'data-parsley-minlength-message' => 'You need more than %s chars',
            ],
            $view->children['id']->vars['attr']
        );
    }

    protected function getParsleyTypeConfig()
    {
        return [
            'enabled' => true,
            'field' => [
                'trigger' => 'focusout',
            ],
        ];
    }

    /**
     * @return FormInterface
     * @throws \Symfony\Component\OptionsResolver\Exception\InvalidOptionsException
     */
    private function createForm(): FormInterface
    {
        return $this->factory->create(TestType::class);
    }

}
