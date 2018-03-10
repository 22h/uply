<?php

namespace App\Entity\Unit;

use Doctrine\ORM\Mapping as ORM;

/**
 * Backup
 *
 * @ORM\Table(name="unit_backup")
 * @ORM\Entity(repositoryClass="App\Repository\Unit\BackupRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
class Backup extends AbstractUnit
{

    use UnitTrait;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $limitSize = 0;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $limitTime = 0;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $initialTime = 0;

    /**
     * @return int
     */
    public function getLimitSize(): int
    {
        return $this->limitSize;
    }

    /**
     * @param int $limitSize
     *
     * @return Backup
     */
    public function setLimitSize(int $limitSize): Backup
    {
        $this->limitSize = $limitSize;

        return $this;
    }

    /**
     * @return int
     */
    public function getLimitTime(): int
    {
        return $this->limitTime;
    }

    /**
     * @param int $limitTime
     *
     * @return Backup
     */
    public function setLimitTime(int $limitTime): Backup
    {
        $this->limitTime = $limitTime;

        return $this;
    }

    /**
     * @return int
     */
    public function getInitialTime(): int
    {
        return $this->initialTime;
    }

    /**
     * @param int $initialTime
     *
     * @return Backup
     */
    public function setInitialTime(int $initialTime): Backup
    {
        $this->initialTime = $initialTime;

        return $this;
    }

    /**
     * @return string
     */
    public static function getIdent(): string
    {
        return 'backup';
    }
}