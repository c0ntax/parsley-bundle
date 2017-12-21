<?php
declare(strict_types=1);

namespace C0ntaX\ParsleyBundle\Constraint;

use C0ntaX\ParsleyBundle\Contracts\ConstraintInterface;
use C0ntaX\ParsleyBundle\Directive\ConstraintErrorMessage;

/**
 * Class AbstractConstraint
 *
 * @package C0ntaX\ParsleyBundle\Constraint
 */
abstract class AbstractConstraint implements ConstraintInterface
{
    public const DATA_ATTRIBUTE_PREFIX = 'data-parsley';

    /** @var ConstraintErrorMessage|null */
    private $errorMessage;

    /**
     * @return null|ConstraintErrorMessage
     */
    public function getErrorMessage(): ?ConstraintErrorMessage
    {
        return $this->errorMessage;
    }

    /**
     * @param string $errorMessage
     * @return AbstractConstraint
     */
    protected function setErrorMessageString(?string $errorMessage): AbstractConstraint
    {
        if ($errorMessage !== null) {
            $this->setErrorMessage(new ConstraintErrorMessage(static::class, $errorMessage));
        }

        return $this;
    }

    /**
     * @param null|ConstraintErrorMessage $errorMessage
     * @return AbstractConstraint
     */
    protected function setErrorMessage(ConstraintErrorMessage $errorMessage): AbstractConstraint
    {
        $this->errorMessage = $errorMessage;

        return $this;
    }

    /**
     * @return string
     */
    protected function getConstraintDataAttribute(): string
    {
        return implode('-', [self::DATA_ATTRIBUTE_PREFIX, static::getConstraintId()]);
    }

    /**
     * @param string $value
     * @param array  $otherAttr
     * @return array
     */
    protected function getMergedViewAttr(string $value, array $otherAttr = []): array
    {
        $attr = $otherAttr;
        if ($this->getErrorMessage() !== null) {
            $attr = array_merge($attr, $this->getErrorMessage()->getViewAttr());
        }
        $attr[$this->getConstraintDataAttribute()] = $value;

        return $attr;
    }
}
