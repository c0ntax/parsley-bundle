<?php

namespace C0ntax\ParsleyBundle\Contracts;

/**
 * Interface DirectiveInterface
 *
 * @package C0ntax\ParsleyBundle\Contracts
 */
interface DirectiveInterface
{
    /**
     * @return array
     */
    public function getViewAttr(): array;
}
