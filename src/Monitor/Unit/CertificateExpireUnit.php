<?php

namespace App\Monitor\Unit;

use App\Entity\Unit\CertificateExpire;
use App\Entity\Unit\UnitInterface;
use App\Monitor\UnitParameterBag;
use App\Monitor\UnitParameterBagFactory;
use App\Repository\Unit\CertificateExpireRepository;
use Spatie\SslCertificate\SslCertificate;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * CertificateExpireUnit
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
class CertificateExpireUnit extends AbstractUnitCheck
{

    /**
     * @var CertificateExpireRepository
     */
    protected $repository;

    /**
     * CertificateExpireUnit constructor.
     *
     * @param EventDispatcherInterface    $eventDispatcher
     * @param UnitParameterBagFactory     $parameterBagFactory
     * @param CertificateExpireRepository $repository
     */
    public function __construct(
        UnitParameterBagFactory $parameterBagFactory,
        EventDispatcherInterface $eventDispatcher,
        CertificateExpireRepository $repository
    ) {
        $this->repository = $repository;

        parent::__construct($parameterBagFactory, $eventDispatcher);
    }

    /**
     * @param UnitInterface $unit
     *
     * @throws \Exception
     */
    public function handle(UnitInterface $unit): void
    {
        parent::handle($unit);
        /** @var CertificateExpire $unit */

        try {
            $certificate = SslCertificate::createForHostName($unit->getDomain());

            $daysLeft = $certificate->expirationDate()->diffInDays();
            $expireSoon = ($unit->getRemindBefore() >= $daysLeft);

            if ($certificate->isExpired()) {
                $this->processingCertificateIsExpired($unit);
            } elseif ($expireSoon) {
                $this->processingExpireSoon($unit, $daysLeft);
            }
        } catch (\Exception $exception) {
            $this->processingError($unit, $exception);
        }
    }

    /**
     * @param CertificateExpire $unit
     * @param \Exception        $exception
     */
    private function processingError(CertificateExpire $unit, \Exception $exception): void
    {
        $this->triggerNotification(
            $unit,
            'Zertifikat defekt',
            'Das SSL Zertifikat auf '.$unit->getDomain().' funktioniert nicht oder wurde falsch eingebunden. '
            .$exception->getMessage(),
            UnitParameterBag::ALERT_RED
        );
    }

    /**
     * @param CertificateExpire $unit
     */
    private function processingCertificateIsExpired(CertificateExpire $unit): void
    {
        $this->triggerNotification(
            $unit,
            'Zertifikat ausgelaufen',
            'Das SSL Zertifikat auf '.$unit->getDomain().' ist abgelaufen',
            UnitParameterBag::ALERT_RED
        );
    }

    /**
     * @param CertificateExpire $unit
     * @param int               $daysLeft
     */
    private function processingExpireSoon(CertificateExpire $unit, int $daysLeft): void
    {
        $this->triggerNotification(
            $unit,
            'Zertifikat läuft ab',
            'Das SSL Zertifikat auf '.$unit->getDomain().' läuft in '.$daysLeft.' Tage(n) aus.',
            UnitParameterBag::ALERT_YELLOW
        );
    }

    /**
     * @return string
     */
    public function entityClass(): string
    {
        return CertificateExpire::class;
    }
}