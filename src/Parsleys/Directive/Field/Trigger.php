<?php
declare(strict_types = 1);

namespace C0ntax\ParsleyBundle\Parsleys\Directive\Field;

use C0ntax\ParsleyBundle\Contracts\DirectiveInterface;
use C0ntax\ParsleyBundle\Parsleys\Directive\Field\Constraint\AbstractConstraint;

/**
 * Class Trigger
 *
 * @package C0ntax\ParsleyBundle\Parsleys\Directive\Field
 */
class Trigger implements DirectiveInterface
{
    /** @var string */
    private $key = 'trigger';

    /** @var string */
    private $value;

    /**
     * Trigger constructor.
     *
     * @param string $value
     */
    public function __construct(string $value)
    {
        $this->setValue($value);
    }

    /**
     * @return array
     */
    public function getViewAttr(): array
    {
        return [
            implode('-', [AbstractConstraint::DATA_ATTRIBUTE_PREFIX, $this->getKey()]) => $this->getValue(),
        ];
    }

    /**
     * @return string
     */
    private function getKey(): string
    {
        return $this->key;
    }

    /**
     * @return string
     */
    private function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param string $value
     * @return Trigger
     */
    private function setValue(string $value): Trigger
    {
        $this->value = $value;

        return $this;
    }
}
