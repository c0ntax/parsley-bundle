<?php
declare(strict_types=1);

namespace C0ntax\ParsleyBundle\Constraint;

/**
 * Class Pattern
 *
 * @package C0ntax\ParsleyBundle\Constraint
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
     */
    public function __construct(string $pattern, string $errorMessage = null)
    {
        $this->setPattern($pattern);
        $this->setErrorMessageString($errorMessage);
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
