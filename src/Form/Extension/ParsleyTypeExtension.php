<?php
declare(strict_types = 1);

namespace C0ntax\ParsleyBundle\Form\Extension;

use C0ntax\ParsleyBundle\Contracts\ConstraintInterface;
use C0ntax\ParsleyBundle\Contracts\DirectiveInterface;
use C0ntax\ParsleyBundle\Contracts\ParsleyInterface;
use C0ntax\ParsleyBundle\Contracts\RemoveInterface;
use C0ntax\ParsleyBundle\Factory\ConstraintFactory;
use C0ntax\ParsleyBundle\Parsleys\AbstractRemove;
use C0ntax\ParsleyBundle\Parsleys\Directive\Field\Trigger;
use C0ntax\ParsleyBundle\Parsleys\RemoveParsleyConstraint;
use C0ntax\ParsleyBundle\Parsleys\RemoveParsleyDirective;
use C0ntax\ParsleyBundle\Parsleys\RemoveSymfonyConstraint;
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

        $parsleys = $options[self::OPTION_NAME];

        $symfonyConstraints = $this->removeFromConstraints(
            array_merge(
                $this->getAnnotatedConstraintsFromForm($form),
                $this->getConstraintsFromForm($form)
            ),
            $this->getRemoveSymfonyConstraintsFromParsleys($parsleys)
        );

        $parsleyConstraints = array_merge(
            $this->createParsleyConstraintsFromValidationConstraints($symfonyConstraints, $form),
            $this->getDirectivesFromParsleys($parsleys)
        );

        $parsleyDirectives = $this->addDefaultDirectives($parsleyConstraints);

        $parsleyDirectives = $this->removeFromConstraints(
            $parsleyDirectives,
            $this->getRemoveParsleyDirectivesFromParsleys($parsleys)
        );

        $this->addParsleyToView($view, $parsleyDirectives);
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
     * @throws \Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults(
                [
                    self::OPTION_NAME => [],
                ]
            )
            ->addAllowedTypes(self::OPTION_NAME, 'array');
    }

    /**
     * @param array $parsleyDirectives
     * @return array
     */
    private function addDefaultDirectives(array $parsleyDirectives): array
    {
        $has = [];
        foreach ($parsleyDirectives as $parsleyDirective) {
            $class = get_class($parsleyDirective);
            if (array_key_exists($class, $has)) {
                $has[$class]++;
            } else {
                $has[$class] = 1;
            }
        }

        if (!array_key_exists(Trigger::class, $has) && count($parsleyDirectives) > 0 && $this->getConfig()['field']['trigger'] !== null) {
            $parsleyDirectives[] = new Trigger($this->getConfig()['field']['trigger']);
        }

        return $parsleyDirectives;
    }

    /**
     * @param ParsleyInterface[] $parsleys
     * @return DirectiveInterface[]
     */
    private function getDirectivesFromParsleys(array $parsleys): array
    {
        $dir = array_values(
            array_filter(
                $parsleys,
                function (ParsleyInterface $parsley) {
                    return $parsley instanceof DirectiveInterface;
                }
            )
        );

        return $dir;
    }

    /**
     * @param ParsleyInterface[] $parsleys
     * @return AbstractRemove[]
     */
    private function getRemoveParsleyDirectivesFromParsleys(array $parsleys): array
    {
        return array_values(
            array_filter(
                $parsleys,
                function (ParsleyInterface $parsley) {
                    return $parsley instanceof RemoveParsleyDirective || $parsley instanceof RemoveParsleyConstraint;
                }
            )
        );
    }

    /**
     * @param ParsleyInterface[] $parsleys
     * @return RemoveSymfonyConstraint[]
     */
    private function getRemoveSymfonyConstraintsFromParsleys(array $parsleys): array
    {
        return array_values(
            array_filter(
                $parsleys,
                function (ParsleyInterface $parsley) {
                    return $parsley instanceof RemoveSymfonyConstraint;
                }
            )
        );
    }

    /**
     * @param \Symfony\Component\Validator\Constraint[]|ConstraintInterface[] $constraints
     * @param RemoveInterface[]                                               $removals
     * @return \Symfony\Component\Validator\Constraint[]|ConstraintInterface[]
     */
    private function removeFromConstraints(array $constraints, array $removals): array
    {
        return array_values(
            array_filter(
                $constraints,
                function ($constraint) use ($removals) {
                    foreach ($removals as $removal) {
                        if ($removal->getClassName() === get_class($constraint)) {
                            return false;
                        }
                    }

                    return true;
                }
            )
        );
    }

    /**
     * @param Constraint[]  $validationConstraints
     * @param FormInterface $form
     * @return ConstraintInterface[]
     * @throws \Exception
     * @throws \InvalidArgumentException
     */
    private function createParsleyConstraintsFromValidationConstraints(
        array $validationConstraints,
        FormInterface $form
    ): array {
        $out = [];
        foreach ($validationConstraints as $validationConstraint) {
            try {
                $parsleyConstraint = ConstraintFactory::createFromValidationConstraint($validationConstraint, $form);
                if ($parsleyConstraint !== null) {
                    $out[] = $parsleyConstraint;
                }
            } catch (\RuntimeException $exception) {
                // Don't care for now!
                // TODO How loud this should be should be configurable
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
    private function addParsleyToView(FormView $view, array $directives): void
    {
        $attr = [];
        $hasTrigger = false;

        foreach ($directives as $directive) {
            foreach ($directive->getViewAttr() as $key => $value) {
                $attr[$key] = $value;
            }
            if ($directive instanceof Trigger) {
                $hasTrigger = true;
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
