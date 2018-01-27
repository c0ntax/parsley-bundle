<?php

namespace C0ntax\ParsleyBundle\Parsleys;

use C0ntax\ParsleyBundle\Contracts\RemoveInterface;

/**
 * Class AbstractRemove
 *
 * @package C0ntax\ParsleyBundle\Parsleys
 */
abstract class AbstractRemove implements RemoveInterface
{
    /** @var string */
    private $className;

    /**
     * AbstractRemove constructor.
     *
     * @param string $className
     * @param string $instanceOf
     * @throws \InvalidArgumentException
     */
    public function __construct(string $className, string $instanceOf)
    {
        if (!class_exists($className)) {
            throw new \InvalidArgumentException('Class '.$className.' does not exist');
        }

        $instancesOf = array_merge(class_implements($className), class_parents($className));

        if (!in_array($instanceOf, $instancesOf, true)) {
            throw new \InvalidArgumentException('Class '.$className.' does not implement '.$instanceOf);
        }

        $this->setClassName($className);
    }

    /**
     * @return string
     */
    public function getClassName(): string
    {
        return $this->className;
    }

    /**
     * @param string $className
     * @return AbstractRemove
     */
    private function setClassName(string $className): AbstractRemove
    {
        $this->className = $className;

        return $this;
    }
}
