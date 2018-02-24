<?php

namespace App\Entity\Unit;

/**
 * UnitTrait
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
trait UnitTrait
{

    /**
     * @var integer
     *
     * @Doctrine\ORM\Mapping\Id
     * @Doctrine\ORM\Mapping\Column(type="integer",options={"unsigned"=true})
     * @Doctrine\ORM\Mapping\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=2000)
     */
    private $url = '';

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $createDate;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $idleTime = self::DEFAULT_IDLE_TIME;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $deactivated = false;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $triggered = null;

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
     * @return string
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @param string $url
     *
     * @return UnitInterface
     */
    public function setUrl(string $url): UnitInterface
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return int
     */
    public function getIdleTime(): ?int
    {
        return $this->idleTime;
    }

    /**
     * @param int $idleTime
     *
     * @return UnitInterface
     */
    public function setIdleTime(int $idleTime): UnitInterface
    {
        $this->idleTime = $idleTime;

        return $this;
    }

    /**
     * @return bool
     */
    public function isDeactivated(): bool
    {
        return $this->deactivated;
    }

    /**
     * @param bool $deactivated
     *
     * @return UnitInterface
     */
    public function setDeactivated(bool $deactivated): UnitInterface
    {
        $this->deactivated = $deactivated;

        return $this;
    }

    /**
     * @return bool
     */
    public function isTriggered(): bool
    {
        return ($this->triggered instanceof \DateTime);
    }

    /**
     * @return UnitInterface
     */
    public function trigger(): UnitInterface
    {
        $this->triggered = (new \DateTime());

        return $this;
    }

    /**
     * @return UnitInterface
     */
    public function removeTrigger(): UnitInterface
    {
        $this->triggered = null;

        return $this;
    }

    /**
     * @param \DateTime $triggered
     *
     * @return UnitInterface
     */
    public function setTriggered(\DateTime $triggered): UnitInterface
    {
        $this->triggered = $triggered;

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getTriggered(): ?\DateTime
    {
        return $this->triggered;
    }

    /**
     * @return \DateTime
     */
    public function getCreateDate(): ?\DateTime
    {
        return $this->createDate;
    }

    /**
     * @param \DateTime $createDate
     *
     * @return UnitInterface
     */
    public function setCreateDate(\DateTime $createDate): UnitInterface
    {
        $this->createDate = $createDate;

        return $this;
    }

    /**
     * @ORM\PrePersist()
     */
    public function beforePersist()
    {
        $this->setCreateDate(new \DateTime());
    }
}
