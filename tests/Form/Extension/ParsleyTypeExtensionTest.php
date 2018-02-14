<?php

namespace C0ntax\ParsleyBundle\Tests\Form\Extension;

use C0ntax\ParsleyBundle\Tests\Fixtures\Form\Type\TestRemovalType;
use C0ntax\ParsleyBundle\Tests\Fixtures\Form\Type\TestTriggerType;
use C0ntax\ParsleyBundle\Tests\Fixtures\Form\Type\TestType;
use C0ntax\ParsleyBundle\Tests\Form\AbstractTypeTestCase;
use Symfony\Component\Form\FormInterface;

class ParsleyTypeExtensionTest extends AbstractTypeTestCase
{
    public function testWithoutRemoval()
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

    public function testRemoval()
    {
        $form = $this->createRemovalForm();
        $form->submit(['id1' => 9, 'id2' => 10, 'id3' => 11]);

        $view = $form->createView();

        // Symfony constraint removal
        self::assertEquals(
            [
                'data-parsley-trigger' => 'focusout',
                'data-parsley-minlength' => '2',
                'data-parsley-minlength-message' => 'You need more than %s chars',
                'data-parsley-maxlength-message' => 'You need less than %s chars', // TODO Make this not be the case!
            ],
            $view->children['id1']->vars['attr']
        );

        // Parsley constraint removal
        self::assertEquals(
            [
                'data-parsley-trigger' => 'focusout',
                'data-parsley-pattern-message' => 'This value is not valid.',
                'data-parsley-pattern' => '/[e]+/',
                'data-parsley-maxlength-message' => 'You need less than %s chars',
                'data-parsley-maxlength' => '10',
            ],
            $view->children['id2']->vars['attr']
        );

        // Both constraint removal
        self::assertEquals(
            [
                'data-parsley-maxlength-message' => 'You need less than %s chars', // TODO Make this not be the case!
                'data-parsley-trigger' => 'focusout', // TODO Make this not be the case!
            ],
            $view->children['id3']->vars['attr']
        );
    }

    public function testTriggerOverride()
    {
        $form = $this->createTriggerForm();
        $view = $form->createView();
        self::assertEquals(
            [
                'data-parsley-required' => 'true',
                'data-parsley-trigger' => 'click',
            ],
            $view->children['check']->vars['attr']
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

    /**
     * @return FormInterface
     * @throws \Symfony\Component\OptionsResolver\Exception\InvalidOptionsException
     */
    private function createRemovalForm(): FormInterface
    {
        return $this->factory->create(TestRemovalType::class);
    }

    /**
     * @return FormInterface
     * @throws \Symfony\Component\OptionsResolver\Exception\InvalidOptionsException
     */
    private function createTriggerForm(): FormInterface
    {
        return $this->factory->create(TestTriggerType::class);
    }

}
