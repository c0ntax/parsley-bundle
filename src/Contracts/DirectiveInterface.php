<?php

namespace C0ntaX\ParsleyBundle\Contracts;

/**
 * Interface DirectiveInterface
 *
 * @package C0ntaX\ParsleyBundle\Contracts
 */
interface DirectiveInterface
{
    /**
     * @return array
     */
    public function getViewAttr(): array;
}
