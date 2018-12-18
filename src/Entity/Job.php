<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Job
 *
 * @ORM\Table(name="job")
 * @ORM\Entity(repositoryClass="App\Repository\JobRepository")
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
class Job
{

    /**
     * @var integer
     *
     * @Doctrine\ORM\Mapping\Id
     * @Doctrine\ORM\Mapping\Column(type="bigint",options={"unsigned"=true})
     * @Doctrine\ORM\Mapping\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $nextCheck = null;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $unitId;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $unitIdent;

    /**
     * getId
     *
     * @return integer
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * setId
     *
     * @param int $id
     *
     * @return self
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getNextCheck(): ?\DateTime
    {
        return $this->nextCheck;
    }

    /**
     * @param \DateTime $nextCheck
     *
     * @return Job
     */
    public function setNextCheck(\DateTime $nextCheck): self
    {
        $this->nextCheck = $nextCheck;

        return $this;
    }

    /**
     * @return int
     */
    public function getUnitId(): ?int
    {
        return $this->unitId;
    }

    /**
     * @param int $unitId
     *
     * @return Job
     */
    public function setUnitId(int $unitId): Job
    {
        $this->unitId = $unitId;

        return $this;
    }

    /**
     * @return string
     */
    public function getUnitIdent(): ?string
    {
        return $this->unitIdent;
    }

    /**
     * @param string $unitIdent
     *
     * @return Job
     */
    public function setUnitIdent(string $unitIdent): Job
    {
        $this->unitIdent = $unitIdent;

        return $this;
    }
}
