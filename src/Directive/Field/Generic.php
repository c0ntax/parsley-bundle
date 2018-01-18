<?php
declare(strict_types=1);

namespace C0ntax\ParsleyBundle\Directive\Field;

use C0ntax\ParsleyBundle\Contracts\DirectiveInterface;
use C0ntax\ParsleyBundle\Directive\Field\Constraint\AbstractConstraint;

/**
 * Class Generic
 *
 * @package C0ntax\ParsleyBundle\Directive\Field
 */
class Generic implements DirectiveInterface
{
    /** @var string A parsley data key */
    private $key;

    /** @var mixed The value for that key */
    private $value;

    /**
     * Generic constructor.
     *
     * @param string      $key
     * @param string|bool $value
     */
    public function __construct(string $key, $value)
    {
        $this->setKey($key);
        $this->setValue($value);
    }

    /**
     * @return array
     */
    public function getViewAttr(): array
    {
        return [
            implode(
                '-',
                [AbstractConstraint::DATA_ATTRIBUTE_PREFIX, $this->getKey()]
            ) => $this->createAttrValue($this->getValue()),
        ];
    }

    /**
     * @param $value
     * @return string
     */
    private function createAttrValue($value): string
    {
        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        } else {
            return $value;
        }
    }

    /**
     * @return string
     */
    private function getKey(): string
    {
        return $this->key;
    }

    /**
     * @param string $key
     * @return Generic
     */
    private function setKey(string $key): Generic
    {
        $this->key = $key;

        return $this;
    }

    /**
     * @return string|bool
     */
    private function getValue()
    {
        return $this->value;
    }

    /**
     * @param string|bool $value
     * @return Generic
     */
    private function setValue($value): Generic
    {
        $this->value = $value;

        return $this;
    }
}
