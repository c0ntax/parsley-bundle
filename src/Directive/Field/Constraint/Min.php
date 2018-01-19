<?php
declare(strict_types = 1);

namespace C0ntax\ParsleyBundle\Directive\Field\Constraint;

/**
 * Class Min
 *
 * @package C0ntax\ParsleyBundle\Directive\Field\Constraint
 */
class Min extends AbstractComparison
{
    /**
     * @return string
     */
    public static function getConstraintId(): string
    {
        return 'min';
    }
}
