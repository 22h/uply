<?php

namespace App\Unit;

use App\Entity\Unit\CertificateExpire;
use App\Repository\Unit\CertificateExpireRepository;
use App\Unit\CertificateExpire\Scrutinizer;

/**
 * CertificateExpireService
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
class CertificateExpireService extends AbstractUnitService
{

    /**
     * StatusCodeService constructor.
     *
     * @param CertificateExpireRepository $repository
     * @param Scrutinizer                 $scrutinizer
     */
    public function __construct(CertificateExpireRepository $repository, Scrutinizer $scrutinizer)
    {
        $this->repository = $repository;
        $this->scrutinizer = $scrutinizer;
    }

    /**
     * @return string
     */
    public function getEntityClass(): string
    {
        return CertificateExpire::class;
    }
}