<?php
declare(strict_types = 1);

namespace C0ntax\ParsleyBundle\Factory;

use C0ntax\ParsleyBundle\Contracts\ConstraintInterface;
use C0ntax\ParsleyBundle\Directive\Field\Constraint\Email;
use C0ntax\ParsleyBundle\Directive\Field\Constraint\Length;
use C0ntax\ParsleyBundle\Directive\Field\Constraint\Max;
use C0ntax\ParsleyBundle\Directive\Field\Constraint\MaxLength;
use C0ntax\ParsleyBundle\Directive\Field\Constraint\Min;
use C0ntax\ParsleyBundle\Directive\Field\Constraint\MinLength;
use C0ntax\ParsleyBundle\Directive\Field\Constraint\Pattern;
use C0ntax\ParsleyBundle\Directive\Field\Constraint\Required;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\AbstractComparison;

/**
 * Class ConstraintFactory
 *
 * @package C0ntax\ParsleyBundle\Directive\Field\Constraint
 */
class ConstraintFactory
{
    /**
     * @param Constraint    $validationConstraint
     * @param FormInterface $form
     * @return ConstraintInterface
     * @throws \Exception
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public static function createFromValidationConstraint(
        Constraint $validationConstraint,
        FormInterface $form
    ): ConstraintInterface {
        // TODO Change this to use the ViewInterface instead of the FormInterface as that makes a lot more sense!

        if ($validationConstraint instanceof \Symfony\Component\Validator\Constraints\NotNull) {
            return new Required($validationConstraint->message);
        } elseif ($validationConstraint instanceof \Symfony\Component\Validator\Constraints\NotBlank) {
            return new Required($validationConstraint->message);
        } if ($validationConstraint instanceof \Symfony\Component\Validator\Constraints\Length) {
            if ($validationConstraint->min !== null && $validationConstraint->max !== null) {
                // TODO Pick a better message!
                return new Length(
                    $validationConstraint->min,
                    $validationConstraint->max,
                    self::convertParameters($validationConstraint->exactMessage)
                );
            } elseif ($validationConstraint->min !== null) {
                return new MinLength(
                    $validationConstraint->min,
                    self::convertParameters($validationConstraint->minMessage)
                );
            } else {
                return new MaxLength(
                    $validationConstraint->max,
                    self::convertParameters($validationConstraint->maxMessage)
                );
            }
        } elseif ($validationConstraint instanceof \Symfony\Component\Validator\Constraints\Regex) {
            return new Pattern($validationConstraint->pattern, self::convertParameters($validationConstraint->message));
        } elseif ($validationConstraint instanceof \Symfony\Component\Validator\Constraints\Email) {
            return new Email(self::convertParameters($validationConstraint->message));
        } elseif ($validationConstraint instanceof AbstractComparison) {
            // This is an interesting case that requires the context of the form element. If any of these validations
            // happen to contain a value that is a string, it is assumed that the string is a dateTime. We should
            // only do dateTime evaluations if the field input type is 'date', otherwise Parsley just wont bother!

            $innerType = $form->getConfig()->getType()->getInnerType();

            if (is_string($validationConstraint->value) && !static::isFormHtml5DateType($form)) {
                throw new \RuntimeException('Date evaluation called on a non-DateType field: '.$form->getName());
            }

            if ($validationConstraint instanceof \Symfony\Component\Validator\Constraints\GreaterThanOrEqual) {
                return new Min(
                    static::convertMinMaxValue($validationConstraint->value),
                    self::convertParameters($validationConstraint->message)
                );
            } elseif ($validationConstraint instanceof \Symfony\Component\Validator\Constraints\GreaterThan) {
                // Bit of a trickey one as isn't an analogous Parsley
                return new Min(
                    static::convertMinMaxValue($validationConstraint->value, true, true),
                    self::convertParameters($validationConstraint->message)
                );
            } elseif ($validationConstraint instanceof \Symfony\Component\Validator\Constraints\LessThanOrEqual) {
                return new Max(
                    static::convertMinMaxValue($validationConstraint->value),
                    self::convertParameters($validationConstraint->message)
                );
            } elseif ($validationConstraint instanceof \Symfony\Component\Validator\Constraints\LessThan) {
                // Bit of a trickey one as isn't an analogous Parsley
                return new Max(
                    static::convertMinMaxValue($validationConstraint->value, true, false),
                    self::convertParameters($validationConstraint->message)
                );
            }

        }

        throw new \RuntimeException('Unsupported Symfony Constraint: '.get_class($validationConstraint));
    }

    /**
     * @param FormInterface $form
     * @return bool
     */
    private static function isFormHtml5DateType(FormInterface $form): bool
    {
        $innerType = $form->getConfig()->getType()->getInnerType();
        $options = $form->getConfig()->getOptions();

        // NOTE: Code below shamelessly lifted from the DateType and DateTimeType view methods as this seems to be
        // the only way to tell if a field is going to be rendered as type="date|datetime"

        $isHtml5 = false;
        if ($innerType instanceof DateType || $innerType instanceof BirthdayType) {
            $isHtml5 = $options['html5'] && 'single_text' === $options['widget'] && DateType::HTML5_FORMAT === $options['format'];
        } elseif ($innerType instanceof DateTimeType) {
            $isHtml5 = $options['html5'] && 'single_text' === $options['widget'] && DateTimeType::HTML5_FORMAT === $options['format'];
        }

        return $isHtml5;
    }

    /**
     * @param int|float|string $value
     * @param bool             $isAdjusted
     * @param bool             $isMin
     * @return string
     * @throws \Exception
     */
    private static function convertMinMaxValue($value, bool $isAdjusted = null, bool $isMin = null): string
    {
        $out = null;
        if (is_int($value)) {
            $adjustment = $isAdjusted ? 1 : 0;
            $adjustment *= $isMin ? 1 : -1;

            return (string) static::adjustIntValue($value, $adjustment);
        } elseif (is_float($value)) {
            $adjustment = $isAdjusted ? 0.0000001 : 0.0;
            $adjustment *= $isMin ? 1 : -1;

            return (string) static::adjustFloatValue($value, $adjustment);
        } elseif (is_string($value)) {
            $adjustment = null;
            if ($isAdjusted) {
                $adjustment = $isMin ? 'add' : 'sub';
            }

            return static::adjustDateTime(new \DateTime($value), $adjustment)->format('Y-m-d H:i:s');
        }
    }

    /**
     * @param int $value
     * @param int $adjustment
     * @return int
     */
    private static function adjustIntValue(int $value, int $adjustment): int
    {
        return $value + $adjustment;
    }

    /**
     * @param float $value
     * @param float $adjustment
     * @return float
     */
    private static function adjustFloatValue(float $value, float $adjustment): float
    {
        return $value + $adjustment;
    }

    /**
     * @param \DateTime   $value
     * @param null|string $action
     * @return \DateTime
     * @throws \Exception
     */
    private static function adjustDateTime(\DateTime $value, ?string $action): \DateTime
    {
        $clone = clone $value;
        if ($action !== null) {
            $adjustment = new \DateInterval('PT1S');
            $clone->$action($adjustment);
        }

        return $clone;
    }

    /**
     * @param \DateTimeImmutable $value
     * @param null|string        $action
     * @return \DateTimeImmutable
     * @throws \Exception
     */
    private static function adjustDateTimeImmutable(\DateTimeImmutable $value, ?string $action): \DateTimeImmutable
    {
        if ($action !== null) {
            $adjustment = new \DateInterval('PT1S');

            return $value->$action($adjustment);
        } else {
            return $value;
        }
    }

    /**
     * @param $string
     * @return string
     */
    private static function convertParameters(string $string): string
    {
        return preg_replace('/{{ [\S]+ }}/', '%s', $string);
    }
}
