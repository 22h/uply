<?php

namespace App\Entity\Unit;

use Doctrine\ORM\Mapping as ORM;

/**
 * GooglePageSpeed
 *
 * @ORM\Table(name="unit_google_page_speed")
 * @ORM\Entity(repositoryClass="App\Repository\Unit\GooglePageSpeedRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
class GooglePageSpeed extends AbstractUnit
{

    use UnitTrait;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $limitDesktop = 0;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $limitMobile = 0;

    /**
     * @return int
     */
    public function getLimitDesktop(): int
    {
        return $this->limitDesktop;
    }

    /**
     * @param int $limitDesktop
     *
     * @return GooglePageSpeed
     */
    public function setLimitDesktop(int $limitDesktop): self
    {
        $this->limitDesktop = $limitDesktop;

        return $this;
    }

    /**
     * @return int
     */
    public function getLimitMobile(): int
    {
        return $this->limitMobile;
    }

    /**
     * @param int $limitMobile
     *
     * @return GooglePageSpeed
     */
    public function setLimitMobile(int $limitMobile): self
    {
        $this->limitMobile = $limitMobile;

        return $this;
    }

    /**
     * @return string
     */
    public function getIdent(): string
    {
        return 'google_page_speed';
    }
}