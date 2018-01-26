<?php
declare(strict_types = 1);

namespace C0ntax\ParsleyBundle\Parsleys\Directive\Field\Constraint;

/**
 * Class Email
 *
 * @package C0ntax\ParsleyBundle\Parsleys\Directive\Field\Constraint
 */
class Email extends AbstractConstraint
{
    /**
     * @return string
     */
    public static function getConstraintId(): string
    {
        return 'type';
    }

    /**
     * @return array
     */
    public function getViewAttr(): array
    {
        return $this->getMergedViewAttr('email');
    }
}
