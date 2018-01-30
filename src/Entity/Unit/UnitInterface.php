<?php

namespace App\Entity\Unit;

/**
 * UnitInterface
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
interface UnitInterface
{
    /**
     * @return int|null
     */
    public function getId(): ?int;

    /**
     * @return string
     */
    public function getUrl(): ?string;

    /**
     * @param string $url
     *
     * @return self
     */
    public function setUrl(string $url): self;

    /**
     * @return int
     */
    public function getIdleTime(): ?int;

    /**
     * @param int $idleTime
     *
     * @return self
     */
    public function setIdleTime(int $idleTime): self;

    /**
     * @return bool
     */
    public function isDeactivated(): bool;

    /**
     * @param bool $deactivated
     *
     * @return self
     */
    public function setDeactivated(bool $deactivated): self;

    /**
     * @return bool
     */
    public function isTriggered(): bool;

    /**
     * @param \DateTime $triggered
     *
     * @return UnitInterface
     */
    public function setTriggered(\DateTime $triggered): UnitInterface;

    /**
     * @return \DateTime|null
     */
    public function getTriggered(): ?\DateTime;

    /**
     * @return \DateTime
     */
    public function getCreateDate(): ?\DateTime;

    /**
     * @param \DateTime $createDate
     *
     * @return self
     */
    public function setCreateDate(\DateTime $createDate): self;

    /**
     * @return string
     */
    public function getIdent(): string;
}