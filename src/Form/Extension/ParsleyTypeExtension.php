<?php
declare(strict_types = 1);

namespace C0ntax\ParsleyBundle\Form\Extension;

use C0ntax\ParsleyBundle\Contracts\ConstraintInterface;
use C0ntax\ParsleyBundle\Contracts\DirectiveInterface;
use C0ntax\ParsleyBundle\Directive\Field\Generic;
use C0ntax\ParsleyBundle\Factory\ConstraintFactory;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class ParsleyTypeExtension
 *
 * @package App\Form\Extension
 */
class ParsleyTypeExtension extends AbstractTypeExtension
{
    /** @var array */
    private $config;

    /** @var ValidatorInterface */
    private $validatorInterface;

    public const OPTION_NAME = 'parsleys';

    /**
     * ParsleyTypeExtension constructor.
     *
     * @param array              $config
     * @param ValidatorInterface $validatorInterface
     */
    public function __construct(array $config, ValidatorInterface $validatorInterface)
    {
        $this->setConfig($config);
        $this->setValidatorInterface($validatorInterface);
    }

    /**
     * {@inheritDoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        if ($this->getConfig()['enabled'] === false) {
            return;
        }

        $constraints = array_merge(
            $this->getAnnotatedConstraintsFromForm($form),
            $this->getConstraintsFromForm($form)
        );

        $parsleyConstraints = array_merge(
            $this->createParsleyConstraintsFromValidationConstraints($constraints),
            $options[self::OPTION_NAME]
        );

        $this->addParsleyToView($view, $parsleyConstraints);
    }

    /**
     * {@inheritDoc}
     */
    public function getExtendedType(): string
    {
        return FormType::class;
    }

    /**
     * @param OptionsResolver $resolver
     * @throws \Symfony\Component\OptionsResolver\Exception\AccessException
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                self::OPTION_NAME => [],
            ]
        );
    }

    /**
     * @param Constraint[] $validationConstraints
     * @return ConstraintInterface[]
     * @throws \InvalidArgumentException
     */
    private function createParsleyConstraintsFromValidationConstraints(array $validationConstraints): array
    {
        $out = [];
        foreach ($validationConstraints as $validationConstraint) {
            try {
                $out[] = ConstraintFactory::createFromValidationConstraint($validationConstraint);
            } catch (\RuntimeException $exception) {
                // Don't care for now!
            }
        }

        return $out;
    }

    /**
     * @param FormInterface $form
     * @return array|\Symfony\Component\Validator\Constraint[]
     */
    private function getConstraintsFromForm(FormInterface $form): array
    {
        return $form->getConfig()->getOptions()['constraints'];
    }

    /**
     * @param FormView             $view
     * @param DirectiveInterface[] $directives
     */
    private function addParsleyToView(FormView $view, array $directives)
    {
        $attr = [];
        if (count($directives) > 0 && $this->getConfig()['field']['trigger'] !== null) {
            $directives[] = new Generic('trigger', $this->getConfig()['field']['trigger']);
        }
        foreach ($directives as $constraint) {
            foreach ($constraint->getViewAttr() as $key => $value) {
                $attr[$key] = $value;
            }
        }

        if (count($attr) > 0) {
            $view->vars['attr'] = array_merge($view->vars['attr'], $attr);
        }
    }

    /**
     * @param FormInterface $form
     * @return array|\Symfony\Component\Validator\Constraint[]
     * @throws \Symfony\Component\Validator\Exception\NoSuchMetadataException
     */
    private function getAnnotatedConstraintsFromForm(FormInterface $form): array
    {
        if ($form->all() === []) {
            // This is a property

            $parent = $form->getParent();

            if ($parent === null) {
                return [];
            }

            $parentConfig = $parent->getConfig();
            $dataClass = $parentConfig->getDataClass();
            if ($dataClass === null) {
                return [];
            }

            /** @var ClassMetadata $classMetadata */
            $classMetadata = $this->getValidatorInterface()->getMetadataFor($dataClass);
            $propertyName = $form->getConfig()->getName();

            $constraints = [];
            foreach ($classMetadata->getPropertyMetadata($propertyName) as $propertyMetadatum) {
                $constraints = array_merge($constraints, $propertyMetadatum->getConstraints());
            }

            return $constraints;
        } else {
            $dataClass = $form->getConfig()->getDataClass();
            if ($dataClass === null) {
                return [];
            }

            /** @var ClassMetadata $classMetadata */
            $classMetadata = $this->getValidatorInterface()->getMetadataFor($dataClass);

            return $classMetadata->getConstraints();
        }
    }

    /**
     * @return array
     */
    private function getConfig(): array
    {
        return $this->config;
    }

    /**
     * @param array $config
     * @return ParsleyTypeExtension
     */
    private function setConfig(array $config): ParsleyTypeExtension
    {
        $this->config = $config;

        return $this;
    }

    /**
     * @return ValidatorInterface
     */
    private function getValidatorInterface(): ValidatorInterface
    {
        return $this->validatorInterface;
    }

    /**
     * @param ValidatorInterface $validatorInterface
     * @return ParsleyTypeExtension
     */
    private function setValidatorInterface(ValidatorInterface $validatorInterface): ParsleyTypeExtension
    {
        $this->validatorInterface = $validatorInterface;

        return $this;
    }
}
