<?php
declare(strict_types = 1);

namespace C0ntax\ParsleyBundle\Parsleys\Directive\Field\Constraint;

/**
 * Class MaxLength
 *
 * @package C0ntax\ParsleyBundle\Parsleys\Directive\Field\Constraint
 */
class MaxLength extends AbstractLength
{
    /**
     * MaxLength constructor.
     *
     * @param int         $max
     * @param string|null $errorMessage
     * @throws \InvalidArgumentException
     */
    public function __construct(int $max, string $errorMessage = null)
    {
        parent::__construct(null, $max, $errorMessage);
    }

    /**
     * @return string
     */
    public static function getConstraintId(): string
    {
        return 'maxlength';
    }

    /**
     * @return array
     */
    public function getViewAttr(): array
    {
        return $this->getMergedViewAttr((string) $this->getMax());
    }
}
