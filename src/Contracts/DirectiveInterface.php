<?php
declare(strict_types = 1);

namespace C0ntax\ParsleyBundle\Contracts;

/**
 * Interface DirectiveInterface
 *
 * @package C0ntax\ParsleyBundle\Contracts
 */
interface DirectiveInterface extends ParsleyInterface
{
    /**
     * @return array
     */
    public function getViewAttr(): array;
}
