<?php
declare(strict_types=1);

namespace C0ntaX\ParsleyBundle\Factory;

use C0ntaX\ParsleyBundle\Constraint\Email;
use C0ntaX\ParsleyBundle\Constraint\Length;
use C0ntaX\ParsleyBundle\Constraint\MaxLength;
use C0ntaX\ParsleyBundle\Constraint\MinLength;
use C0ntaX\ParsleyBundle\Constraint\Pattern;
use C0ntaX\ParsleyBundle\Contracts\ConstraintInterface;
use Symfony\Component\Validator\Constraint;

/**
 * Class ConstraintFactory
 *
 * @package C0ntaX\ParsleyBundle\Constraint
 */
class ConstraintFactory
{
    /**
     * @param Constraint $validationConstraint
     * @return ConstraintInterface
     * @throws \RuntimeException
     */
    public static function createFromValidationConstraint(Constraint $validationConstraint): ConstraintInterface
    {
        if ($validationConstraint instanceof \Symfony\Component\Validator\Constraints\Length) {
            if ($validationConstraint->min !== null && $validationConstraint->max !== null) {
                // TODO Pick a better message!
                return new Length($validationConstraint->min, $validationConstraint->max, self::convertParameters($validationConstraint->exactMessage));
            } elseif ($validationConstraint->min !== null) {
                return new MinLength($validationConstraint->min, self::convertParameters($validationConstraint->minMessage));
            } else {
                return new MaxLength($validationConstraint->max, self::convertParameters($validationConstraint->maxMessage));
            }
        } elseif ($validationConstraint instanceof \Symfony\Component\Validator\Constraints\Regex) {
            return new Pattern($validationConstraint->pattern, self::convertParameters($validationConstraint->message));
        } elseif ($validationConstraint instanceof \Symfony\Component\Validator\Constraints\Email) {
            return new Email(self::convertParameters($validationConstraint->message));
        }

        throw new \RuntimeException('Unsupported Symfony Constraint: '.get_class($validationConstraint));
    }

    /**
     * @param $string
     * @return string
     */
    private static function convertParameters(string $string): string
    {
        return preg_replace('/\{\{ [^\s]+ \}\}/', '%s', $string);
    }
}
