<?php

namespace C0ntax\ParsleyBundle\Tests\Fixtures\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class TestRemovalEntity
{
    /**
     * @var string|null
     *
     * @Assert\Regex(pattern="/^[a-z]{10}$/", message="This doesn't look like an id")
     * @Assert\Length(max=10)
     */
    private $id1;

    /**
     * @var string|null
     *
     * @Assert\Regex(pattern="/^[a-z]{10}$/", message="This doesn't look like an id")
     * @Assert\Length(max=10)
     */
    private $id2;

    /**
     * @var string|null
     *
     * @Assert\Regex(pattern="/^[a-z]{10}$/", message="This doesn't look like an id")
     * @Assert\Length(max=10)
     */
    private $id3;

    /**
     * @return null|string
     */
    public function getId1(): ?string
    {
        return $this->id1;
    }

    /**
     * @param null|string $id1
     *
     * @return TestRemovalEntity
     */
    public function setId1(?string $id1)
    {
        $this->id1 = $id1;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getId2(): ?string
    {
        return $this->id2;
    }

    /**
     * @param null|string $id2
     *
     * @return TestRemovalEntity
     */
    public function setId2(?string $id2)
    {
        $this->id2 = $id2;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getId3(): ?string
    {
        return $this->id3;
    }

    /**
     * @param null|string $id3
     *
     * @return TestRemovalEntity
     */
    public function setId3(?string $id3)
    {
        $this->id3 = $id3;

        return $this;
    }
}
