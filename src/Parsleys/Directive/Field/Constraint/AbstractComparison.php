<?php
declare(strict_types = 1);

namespace C0ntax\ParsleyBundle\Parsleys\Directive\Field\Constraint;

/**
 * Class AbstractComparison
 *
 * @package C0ntax\ParsleyBundle\Parsleys\Directive\Field\Constraint
 */
abstract class AbstractComparison extends AbstractConstraint
{
    /** @var int|float|\DateTime */
    private $value;

    /**
     * Min constructor.
     *
     * @param \DateTime|float|int $value
     * @param string|null         $errorMessage
     * @throws \InvalidArgumentException
     */
    public function __construct($value, string $errorMessage = null)
    {
        $this->setValue($value);
        parent::__construct($errorMessage);
    }

    /**
     * @return array
     */
    public function getViewAttr(): array
    {
        $value = null;
        if ($this->getValue() instanceof \DateTime || $this->getValue() instanceof \DateTimeImmutable) {
            $value = $this->getValue()->format('Y-m-d H:i:s');
        } else {
            $value = (string) $this->getValue();
        }

        return $this->getMergedViewAttr($value);
    }

    /**
     * @return \DateTime|float|int
     */
    private function getValue()
    {
        return $this->value;
    }

    /**
     * @param \DateTime|float|int $min
     * @return AbstractComparison
     */
    private function setValue($min): AbstractComparison
    {
        $this->value = $min;

        return $this;
    }
}
