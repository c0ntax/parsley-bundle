<?php
declare(strict_types=1);

namespace C0ntax\ParsleyBundle\Constraint;

/**
 * Class MinLength
 *
 * @package C0ntax\ParsleyBundle\Constraint
 */
class MinLength extends AbstractLength
{
    /**
     * MinLength constructor.
     *
     * @param int|null    $min
     * @param string|null $errorMessage
     */
    public function __construct(int $min, string $errorMessage = null)
    {
        parent::__construct($min, null, $errorMessage);
    }

    /**
     * @return string
     */
    public static function getConstraintId(): string
    {
        return 'minlength';
    }

    /**
     * @return array
     */
    public function getViewAttr(): array
    {
        return $this->getMergedViewAttr((string) $this->getMin());
    }
}
