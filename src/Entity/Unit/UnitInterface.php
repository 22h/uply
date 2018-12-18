<?php

namespace App\Entity\Unit;

/**
 * MonitorInterface
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
     * @return int
     */
    public function getTriggeredIdleTime(): ?int;

    /**
     * @param int $idleTime
     *
     * @return self
     */
    public function setTriggeredIdleTime(int $idleTime): self;

    /**
     * @return bool
     */
    public function isDeactivated(): bool;

    /**
     * @return string
     */
    public function getActualLevel(): ?string;

    /**
     * @param string $level
     *
     * @return UnitInterface
     */
    public function setActualLevel(?string $level): self;

    /**
     * @param bool $deactivated
     *
     * @return self
     */
    public function setDeactivated(bool $deactivated): self;

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
     * @return null|string
     */
    public function getDomain(): ?string;

    /**
     * @return string
     */
    public static function getIdent(): string;
}