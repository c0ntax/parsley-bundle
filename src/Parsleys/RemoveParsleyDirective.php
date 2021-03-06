<?php
declare(strict_types = 1);

namespace C0ntax\ParsleyBundle\Parsleys;

use C0ntax\ParsleyBundle\Contracts\DirectiveInterface;

/**
 * Class RemoveParsleyConstraint
 *
 * Used to remove Parsley Directives after they've been put in
 *
 * @package C0ntax\ParsleyBundle\Parsleys
 */
class RemoveParsleyDirective extends AbstractRemove
{
    /**
     * RemoveParsley constructor.
     *
     * @param string $className
     * @throws \InvalidArgumentException
     */
    public function __construct(string $className)
    {
        parent::__construct($className, DirectiveInterface::class);
    }
}
