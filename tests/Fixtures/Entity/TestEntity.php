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
     * @var int|null
     */
    private $int;

    /**
     * @var float|null
     */
    private $float;

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
     * @var \DateTime|null
     */
    private $dateNotHtml5;

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

    /**
     * @return int|null
     */
    public function getInt(): ?int
    {
        return $this->int;
    }

    /**
     * @param int|null $int
     * @return TestEntity
     */
    public function setInt(?int $int)
    {
        $this->int = $int;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getFloat(): ?float
    {
        return $this->float;
    }

    /**
     * @param float|null $float
     * @return TestEntity
     */
    public function setFloat(?float $float)
    {
        $this->float = $float;

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getDateNotHtml5(): ?\DateTime
    {
        return $this->dateNotHtml5;
    }

    /**
     * @param \DateTime|null $dateNotHtml5
     * @return TestEntity
     */
    public function setDateNotHtml5(?\DateTime $dateNotHtml5)
    {
        $this->dateNotHtml5 = $dateNotHtml5;

        return $this;
    }
}
