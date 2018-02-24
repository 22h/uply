<?php

namespace App\Entity\Unit;

use Doctrine\ORM\Mapping as ORM;

/**
 * CertificateExpire
 *
 * @ORM\Table(name="unit_certificate_expire")
 * @ORM\Entity(repositoryClass="App\Repository\Unit\CertificateExpireRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
class CertificateExpire extends AbstractUnit
{
    use UnitTrait;

    const DEFAULT_EXPIRE_WARNING_DAYS = 7;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $remindBefore = self::DEFAULT_EXPIRE_WARNING_DAYS;

    /**
     * @return int
     */
    public function getRemindBefore(): int
    {
        return $this->remindBefore;
    }

    /**
     * @param int $remindBefore
     *
     * @return CertificateExpire
     */
    public function setRemindBefore(int $remindBefore): self
    {
        $this->remindBefore = $remindBefore;

        return $this;
    }

    /**
     * @return string
     */
    public static function getIdent(): string
    {
        return 'certificate_expire';
    }
}