<?php

namespace C0ntax\ParsleyBundle\Tests\Fixtures\Form\Type;

use C0ntax\ParsleyBundle\Parsleys\Directive\Field\Constraint\MaxLength;
use C0ntax\ParsleyBundle\Parsleys\Directive\Field\Constraint\MinLength;
use C0ntax\ParsleyBundle\Parsleys\Directive\Field\ConstraintErrorMessage;
use C0ntax\ParsleyBundle\Parsleys\RemoveParsleyConstraint;
use C0ntax\ParsleyBundle\Parsleys\RemoveSymfonyConstraint;
use C0ntax\ParsleyBundle\Tests\Fixtures\Entity\TestRemovalEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;

class TestRemovalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'id1',
                TextType::class,
                [
                    'constraints' => new Regex('/[e]+/'),
                    'parsleys' => [
                        new MinLength(2, 'You need more than %s chars'),
                        new ConstraintErrorMessage(MaxLength::class, 'You need less than %s chars'),
                        new RemoveSymfonyConstraint(Regex::class),
                        new RemoveSymfonyConstraint(Length::class),
                    ],
                ]
            )
            ->add(
                'id2',
                TextType::class,
                [
                    'constraints' => new Regex('/[e]+/'),
                    'parsleys' => [
                        new MinLength(2, 'You need more than %s chars'),
                        new ConstraintErrorMessage(MaxLength::class, 'You need less than %s chars'),
                        new RemoveParsleyConstraint(MinLength::class),
                    ],
                ]
            )
            ->add(
                'id3',
                TextType::class,
                [
                    'constraints' => new Regex('/[e]+/'),
                    'parsleys' => [
                        new MinLength(2, 'You need more than %s chars'),
                        new ConstraintErrorMessage(MaxLength::class, 'You need less than %s chars'),
                        new RemoveSymfonyConstraint(Regex::class),
                        new RemoveSymfonyConstraint(Length::class),
                        new RemoveParsleyConstraint(MinLength::class),
                    ],
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => TestRemovalEntity::class]);
    }
}
