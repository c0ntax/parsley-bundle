<?php

namespace C0ntax\ParsleyBundle\Directive\Field\Constraint;

/**
 * Class Required
 *
 * @package C0ntax\ParsleyBundle\Directive\Field\Constraint
 */
class Required extends AbstractConstraint
{
    /**
     * @return string
     */
    public static function getConstraintId(): string
    {
        return 'required';
    }

    /**
     * @return array
     */
    public function getViewAttr(): array
    {
        return $this->getMergedViewAttr('true');
    }
}
