<?php

namespace C0ntax\ParsleyBundle\Tests\Fixtures\Form\Type;

use C0ntax\ParsleyBundle\Parsleys\Directive\Field\Constraint\Required;
use C0ntax\ParsleyBundle\Parsleys\Directive\Field\Trigger;
use C0ntax\ParsleyBundle\Tests\Fixtures\Entity\TriggerEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TestTriggerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'check',
                CheckboxType::class,
                [
                    'parsleys' => [
                        new Required(),
                        new Trigger('click'),
                    ],
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => TriggerEntity::class]);
    }
}
