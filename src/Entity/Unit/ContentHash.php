<?php

namespace App\Entity\Unit;

use Doctrine\ORM\Mapping as ORM;

/**
 * ContentHash
 *
 * @ORM\Table(name="unit_content_hash")
 * @ORM\Entity(repositoryClass="App\Repository\Unit\ContentHashRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
class ContentHash extends AbstractUnit
{

    use UnitTrait;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=32, nullable=false)
     */
    private $hash = '';

    /**
     * @return string
     */
    public function getHash(): string
    {
        return $this->hash;
    }

    /**
     * @param string $hash
     *
     * @return ContentHash
     */
    public function setHash(string $hash): ContentHash
    {
        $this->hash = $hash;

        return $this;
    }

    /**
     * @return string
     */
    public static function getIdent(): string
    {
        return 'content_hash';
    }
}