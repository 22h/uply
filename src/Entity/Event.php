<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Event
 *
 * @ORM\Table(name="event")
 * @ORM\Entity(repositoryClass="App\Repository\EventRepository")
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
class Event
{

    const UNIT_SPECIAL_TYPE_SLEEP = 'sleep';

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
     * @return Event
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
     * @return Event
     */
    public function setUnitId(int $unitId): Event
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
     * @return Event
     */
    public function setUnitIdent(string $unitIdent): Event
    {
        $this->unitIdent = $unitIdent;

        return $this;
    }

    /**
     * @return bool
     */
    public function isExitEvent(): bool
    {
        return $this->isSpecialEvent(self::UNIT_SPECIAL_TYPE_EXIT);
    }

    /**
     * @return bool
     */
    public function isSleepEvent(): bool
    {
        return $this->isSpecialEvent(self::UNIT_SPECIAL_TYPE_SLEEP);
    }

    /**
     * @param string $eventName
     *
     * @return bool
     */
    protected function isSpecialEvent(string $eventName): bool
    {
        return (!is_null($this->unitIdent) && $this->unitIdent === $eventName);
    }
}
