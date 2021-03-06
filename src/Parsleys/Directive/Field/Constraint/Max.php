<?php
declare(strict_types = 1);

namespace C0ntax\ParsleyBundle\Parsleys\Directive\Field\Constraint;

/**
 * Class Min
 *
 * @package C0ntax\ParsleyBundle\Parsleys\Directive\Field\Constraint
 */
class Max extends AbstractComparison
{
    /**
     * @return string
     */
    public static function getConstraintId(): string
    {
        return 'max';
    }
}
