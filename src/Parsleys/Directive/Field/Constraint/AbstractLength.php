<?php
declare(strict_types = 1);

namespace C0ntax\ParsleyBundle\Parsleys\Directive\Field\Constraint;

/**
 * Class AbstractLength
 *
 * @package C0ntax\ParsleyBundle\Parsleys\Directive\Field\Constraint
 */
abstract class AbstractLength extends AbstractConstraint
{
    /** @var int|null $min */
    private $min;

    /** @var int|null $max */
    private $max;

    /**
     * Length constructor.
     *
     * @param int|null    $min
     * @param int|null    $max
     * @param string|null $errorMessage
     * @throws \InvalidArgumentException
     */
    public function __construct(int $min = null, int $max = null, string $errorMessage = null)
    {
        $this->setMin($min);
        $this->setMax($max);
        parent::__construct($errorMessage);
    }

    /**
     * @return int
     */
    protected function getMin(): int
    {
        return $this->min;
    }

    /**
     * @return int
     */
    protected function getMax(): int
    {
        return $this->max;
    }

    /**
     * @param int $min
     * @return AbstractLength
     */
    private function setMin(?int $min): AbstractLength
    {
        $this->min = $min;

        return $this;
    }

    /**
     * @param int $max
     * @return AbstractLength
     */
    private function setMax(?int $max): AbstractLength
    {
        $this->max = $max;

        return $this;
    }
}
