<?php
declare(strict_types=1);

namespace C0ntax\ParsleyBundle\Constraint;

/**
 * Class Length
 *
 * @package C0ntax\ParsleyBundle\Constraint
 */
class Length extends AbstractLength
{
    /**
     * Length constructor.
     *
     * @param int         $min
     * @param int         $max
     * @param string|null $errorMessage
     */
    public function __construct(int $min, int $max, string $errorMessage = null)
    {
        parent::__construct($min, $max, $errorMessage);
    }

    /**
     * @return string
     */
    public static function getConstraintId(): string
    {
        return 'length';
    }

    /**
     * @return array
     */
    public function getViewAttr(): array
    {
        return $this->getMergedViewAttr('['.implode(', ', [$this->getMin(), $this->getMax()]).']');
    }
}
