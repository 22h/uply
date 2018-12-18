<?php

namespace App\Service;

use Symfony\Component\Filesystem\Filesystem;

/**
 * AliveService
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
class AliveService
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var string
     */
    private $heartbeatFile;

    /**
     * @param Filesystem $filesystem
     * @param string     $heartbeatFile
     */
    public function __construct(Filesystem $filesystem, string $heartbeatFile)
    {
        $this->filesystem = $filesystem;
        $this->heartbeatFile = $heartbeatFile;
    }

    public function heartbeat(): void
    {
        $this->filesystem->touch($this->heartbeatFile);
    }

    /**
     * @param int|null $seconds
     *
     * @return bool
     */
    public function isStillAlive(?int $seconds = 10): bool
    {
        if (!$this->filesystem->exists($this->heartbeatFile)) {
            return false;
        }

        return (time() - $seconds < filectime($this->heartbeatFile));
    }
}