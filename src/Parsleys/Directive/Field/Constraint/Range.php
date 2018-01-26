<?php
declare(strict_types = 1);

namespace C0ntax\ParsleyBundle\Parsleys\Directive\Field\Constraint;

/**
 * Class Range
 *
 * @package C0ntax\ParsleyBundle\Parsleys\Directive\Field\Constraint
 */
class Range extends AbstractConstraint
{
    /** @var int|float|\DateTime|\DateTimeImmutable */
    private $min;

    /** @var int|float|\DateTime|\DateTimeImmutable */
    private $max;

    /**
     * Range constructor.
     *
     * @param int|float|\DateTime|\DateTimeImmutable $min
     * @param int|float|\DateTime|\DateTimeImmutable $max
     * @param null|string                            $errorMessageString
     * @throws \InvalidArgumentException
     */
    public function __construct($min, $max, ?string $errorMessageString = null)
    {
        $this->setMin($min);
        $this->setMax($max);
        parent::__construct($errorMessageString);
    }

    /**
     * @return string
     */
    public static function getConstraintId(): string
    {
        return 'range';
    }

    /**
     * @return array
     */
    public function getViewAttr(): array
    {
        $minValue = $this->createStringValue($this->getMin());
        $maxValue = $this->createStringValue($this->getMax());

        return $this->getMergedViewAttr('["'.$minValue.'", "'.$maxValue.'"]');
    }

    /**
     * @param $value
     * @return string
     */
    private function createStringValue($value): string
    {
        if ($value instanceof \DateTime || $value instanceof \DateTimeImmutable) {
            return $value->format('Y-m-d H:i:s');
        } else {
            return (string) $value;
        }
    }

    /**
     * @return \DateTime|\DateTimeImmutable|float|int
     */
    private function getMin()
    {
        return $this->min;
    }

    /**
     * @param \DateTime|\DateTimeImmutable|float|int $min
     * @return Range
     */
    private function setMin($min): Range
    {
        $this->min = $min;

        return $this;
    }

    /**
     * @return \DateTime|\DateTimeImmutable|float|int
     */
    private function getMax()
    {
        return $this->max;
    }

    /**
     * @param \DateTime|\DateTimeImmutable|float|int $max
     * @return Range
     */
    private function setMax($max): Range
    {
        $this->max = $max;

        return $this;
    }
}
