<?php
declare(strict_types = 1);

namespace C0ntax\ParsleyBundle\Contracts;

use C0ntax\ParsleyBundle\Parsleys\Directive\Field\ConstraintErrorMessage;

/**
 * Interface ConstraintInterface
 *
 * @package C0ntax\ParsleyBundle\Parsleys\Directive\Field\Constraint
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
