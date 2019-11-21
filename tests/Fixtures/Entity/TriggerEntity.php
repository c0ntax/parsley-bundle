<?php

namespace C0ntax\ParsleyBundle\Tests\Fixtures\Entity;

class TriggerEntity
{
    private $check = false;

    /**
     * @return bool
     */
    public function isCheck(): bool
    {
        return $this->check;
    }

    /**
     * @param bool $check
     *
     * @return TriggerEntity
     */
    public function setCheck(bool $check)
    {
        $this->check = $check;

        return $this;
    }
}
