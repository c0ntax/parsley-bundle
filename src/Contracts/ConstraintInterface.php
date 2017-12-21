<?php
declare(strict_types=1);

namespace C0ntaX\ParsleyBundle\Contracts;

use C0ntaX\ParsleyBundle\Directive\ConstraintErrorMessage;

/**
 * Interface ConstraintInterface
 *
 * @package C0ntaX\ParsleyBundle\Constraint
 */
interface ConstraintInterface extends DirectiveInterface
{
    /**
     * @return null|ConstraintErrorMessage
     */
    public function getErrorMessage(): ?ConstraintErrorMessage;

    /**
     * @return string
     */
    public static function getConstraintId(): string;
}
