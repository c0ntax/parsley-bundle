<?php
declare(strict_types = 1);

namespace C0ntax\ParsleyBundle\Parsleys\Directive\Field\Constraint;

/**
 * Class Pattern
 *
 * @package C0ntax\ParsleyBundle\Parsleys\Directive\Field\Constraint
 */
class Pattern extends AbstractConstraint
{
    /** @var string */
    private $pattern;

    /**
     * Pattern constructor.
     *
     * @param string      $pattern
     * @param string|null $errorMessage
     * @throws \InvalidArgumentException
     */
    public function __construct(string $pattern, string $errorMessage = null)
    {
        $this->setPattern($pattern);
        parent::__construct($errorMessage);
    }

    /**
     * @return string
     */
    public static function getConstraintId(): string
    {
        return 'pattern';
    }

    /**
     * @return array
     */
    public function getViewAttr(): array
    {
        return $this->getMergedViewAttr($this->getPattern());
    }

    /**
     * @return string
     */
    private function getPattern(): string
    {
        return $this->pattern;
    }

    /**
     * @param string $pattern
     * @return Pattern
     */
    private function setPattern(string $pattern): Pattern
    {
        $this->pattern = $pattern;

        return $this;
    }
}
