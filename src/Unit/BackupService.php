<?php

namespace App\Unit;

use App\Entity\Unit\Backup;
use App\Repository\Unit\BackupRepository;
use App\Unit\Backup\Scrutinizer;

/**
 * BackupService
 *
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
class BackupService extends AbstractUnitService
{

    /**
     * StatusCodeService constructor.
     *
     * @param BackupRepository $repository
     * @param Scrutinizer          $scrutinizer
     */
    public function __construct(BackupRepository $repository, Scrutinizer $scrutinizer)
    {
        $this->repository = $repository;
        $this->scrutinizer = $scrutinizer;
    }

    /**
     * @return string
     */
    public function getEntityClass(): string
    {
        return Backup::class;
    }
}
