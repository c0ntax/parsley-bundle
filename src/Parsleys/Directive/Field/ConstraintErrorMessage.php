<?php

namespace C0ntax\ParsleyBundle\Parsleys\Directive\Field;

use C0ntax\ParsleyBundle\Contracts\ConstraintInterface;
use C0ntax\ParsleyBundle\Contracts\DirectiveInterface;
use C0ntax\ParsleyBundle\Parsleys\Directive\Field\Constraint\AbstractConstraint;

/**
 * Class ErrorMessage
 *
 * @package C0ntax\ParsleyBundle\Parsleys\Directive
 */
class ConstraintErrorMessage implements DirectiveInterface
{
    /** @var string */
    private $constraintId;

    /** @var string */
    private $errorMessage;

    /**
     * ErrorMessage constructor.
     *
     * @param string $class
     * @param string $errorMessage
     * @throws \InvalidArgumentException
     */
    public function __construct(string $class, string $errorMessage)
    {
        $this->setConstraintId($this->getConstraintIdFromClass($class));
        $this->setErrorMessage($errorMessage);
    }

    /**
     * @return array
     */
    public function getViewAttr(): array
    {
        return [
            implode(
                '-',
                [AbstractConstraint::DATA_ATTRIBUTE_PREFIX, $this->getConstraintId(), 'message']
            ) => $this->getErrorMessage(),
        ];
    }

    /**
     * @param string $class
     * @return string
     * @throws \InvalidArgumentException
     */
    private function getConstraintIdFromClass(string $class): string
    {
        if (!class_exists($class)) {
            throw new \InvalidArgumentException(
                $class.' is not a class and therefore doesn\'t implement '.ConstraintInterface::class
            );
        }

        if (array_key_exists(ConstraintInterface::class, class_implements($class))) {
            /** @var ConstraintInterface $class */

            return $class::getConstraintId();
        }

        throw new \InvalidArgumentException('Class '.$class.' does not implement '.ConstraintInterface::class);
    }

    /**
     * @return string
     */
    private function getConstraintId(): string
    {
        return $this->constraintId;
    }

    /**
     * @param string $constraintId
     * @return ConstraintErrorMessage
     */
    private function setConstraintId(string $constraintId): ConstraintErrorMessage
    {
        $this->constraintId = $constraintId;

        return $this;
    }

    /**
     * @return string
     */
    private function getErrorMessage(): string
    {
        return $this->errorMessage;
    }

    /**
     * @param string $errorMessage
     * @return ConstraintErrorMessage
     */
    private function setErrorMessage(string $errorMessage): ConstraintErrorMessage
    {
        $this->errorMessage = $errorMessage;

        return $this;
    }
}
