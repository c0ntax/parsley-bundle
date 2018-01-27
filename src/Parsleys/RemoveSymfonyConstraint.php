<?php
declare(strict_types = 1);

namespace C0ntax\ParsleyBundle\Parsleys;

use Symfony\Component\Validator\Constraint;

/**
 * Class RemoveSymfonyConstraint
 *
 * Used to remove Parsley Directives after they've been put in
 *
 * @package C0ntax\ParsleyBundle\Parsleys
 */
class RemoveSymfonyConstraint extends AbstractRemove
{
    /**
     * RemoveParsley constructor.
     *
     * @param string $className
     * @throws \InvalidArgumentException
     */
    public function __construct(string $className)
    {
        parent::__construct($className, Constraint::class);
    }
}
