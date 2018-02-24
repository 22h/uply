<?php

namespace App\Entity\Unit;

use Doctrine\ORM\Mapping as ORM;

/**
 * StatusCode
 *
 * @ORM\Table(name="unit_status_code")
 * @ORM\Entity(repositoryClass="App\Repository\Unit\StatusCodeRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
class StatusCode extends AbstractUnit
{

    use UnitTrait;

    const DEFAULT_STATUS_CODE = 200;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $statusCode = self::DEFAULT_STATUS_CODE;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $bustCache = false;

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @param int $statusCode
     *
     * @return StatusCode
     */
    public function setStatusCode(int $statusCode): self
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * @return bool
     */
    public function isBustCache(): bool
    {
        return $this->bustCache;
    }

    /**
     * @param bool $bustCache
     *
     * @return StatusCode
     */
    public function setBustCache(bool $bustCache): self
    {
        $this->bustCache = $bustCache;

        return $this;
    }

    /**
     * @return string
     */
    public static function getIdent(): string
    {
        return 'status_code';
    }
}