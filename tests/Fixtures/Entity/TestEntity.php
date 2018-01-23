<?php

namespace C0ntax\ParsleyBundle\Tests\Fixtures\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class TestEntity
{
    /**
     * @var string
     * @Assert\Regex(pattern="/^[a-z]{10}$/", message="This doesn't look like an id")
     * @Assert\Length(max=10)
     */
    private $id;

    /**
     * @var string
     * @Assert\Email(message="This isn't an email address")
     */
    private $email;

    /**
     * @var string
     * @Assert\Length(max=50)
     */
    private $string;

    /**
     * @var \DateTime|null
     */
    private $date;

    /**
     * @var \DateTime|null
     */
    private $dob;

    /**
     * @return string
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return TestEntity
     */
    public function setId(string $id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return TestEntity
     */
    public function setEmail(string $email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getString(): ?string
    {
        return $this->string;
    }

    /**
     * @param string $string
     * @return TestEntity
     */
    public function setString(string $string)
    {
        $this->string = $string;

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    /**
     * @param \DateTime|null $date
     * @return TestEntity
     */
    public function setDate(?\DateTime $date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getDob(): ?\DateTime
    {
        return $this->dob;
    }

    /**
     * @param \DateTime|null $dob
     * @return TestEntity
     */
    public function setDob(?\DateTime $dob)
    {
        $this->dob = $dob;

        return $this;
    }
}
