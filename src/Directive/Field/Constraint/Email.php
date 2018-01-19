<?php
declare(strict_types = 1);

namespace C0ntax\ParsleyBundle\Directive\Field\Constraint;

/**
 * Class Email
 *
 * @package C0ntax\ParsleyBundle\Directive\Field\Constraint
 */
class Email extends AbstractConstraint
{
    /**
     * Email constructor.
     *
     * @param string|null $errorMessage
     * @throws \InvalidArgumentException
     */
    public function __construct(string $errorMessage = null)
    {
        $this->setErrorMessageString($errorMessage);
    }

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
