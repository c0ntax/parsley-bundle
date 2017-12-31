<?php

namespace C0ntax\ParsleyBundle\Tests\Fixtures\Form\Type;

use C0ntax\ParsleyBundle\Constraint\MaxLength;
use C0ntax\ParsleyBundle\Constraint\MinLength;
use C0ntax\ParsleyBundle\Directive\ConstraintErrorMessage;
use C0ntax\ParsleyBundle\Tests\Fixtures\Entity\TestEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Regex;

class TestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'id',
                TextType::class,
                [
                    'constraints' => new Regex('/[e]+/'),
                    'parsleys' => [
                        new MinLength(2, 'You need more than %s chars'),
                        new ConstraintErrorMessage(MaxLength::class, 'You need less than %s chars'),
                    ],
                ]
            )
//            ->add('email', TextType::class)
//            ->add('string', TextType::class, ['constraints' => [new NotBlank()]])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => TestEntity::class]);
    }
}
