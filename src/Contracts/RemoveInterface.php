<?php
declare(strict_types = 1);

namespace C0ntax\ParsleyBundle\Contracts;

/**
 * Interface RemoveInterface
 *
 * @package C0ntax\ParsleyBundle\Contracts
 */
interface RemoveInterface extends ParsleyInterface
{
    /**
     * Get the fully qualified classname that is to be removed from the view
     *
     * @return string
     */
    public function getClassName(): string;
}
