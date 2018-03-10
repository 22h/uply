<?php

namespace App\Unit\CertificateExpire;

use App\Entity\Unit\CertificateExpire;
use App\Monitor\Event\NotifyEventDispatcher;
use App\Monitor\UnitParameterBag;
use App\Unit\ScrutinizerInterface;
use Spatie\SslCertificate\SslCertificate;

/**
 * Scrutinizer
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
class Scrutinizer implements ScrutinizerInterface
{
    /**
     * @var NotifyEventDispatcher
     */
    private $notifyEventDispatcher;

    /**
     * Scrutinizer constructor.
     *
     * @param NotifyEventDispatcher $notifyEventDispatcher
     */
    public function __construct(NotifyEventDispatcher $notifyEventDispatcher)
    {
        $this->notifyEventDispatcher = $notifyEventDispatcher;
    }

    /**
     * @param CertificateExpire $unit
     *
     * @throws \Exception
     */
    public function scrutinize($unit): void
    {
        try {
            $certificate = SslCertificate::createForHostName($unit->getDomain());
            $daysLeft = $certificate->expirationDate()->diffInDays();
            $expireSoon = ($unit->getRemindBefore() >= $daysLeft);
            if ($certificate->isExpired()) {
                $this->processingCertificateIsExpired($unit);
                $unit->trigger();
            } elseif ($expireSoon) {
                $this->processingExpireSoon($unit, $daysLeft);
                $unit->trigger();
            } elseif($unit->isTriggered()) {
                $this->processingCertificateValidAgain($unit);
                $unit->removeTrigger();
            }
        } catch (\Exception $exception) {
            $this->processingError($unit, $exception);
            $unit->trigger();
        }
    }

    /**
     * @param CertificateExpire $unit
     * @param \Exception        $exception
     */
    private function processingError(CertificateExpire $unit, \Exception $exception): void
    {
        $this->notifyEventDispatcher->dispatchNotification(
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
        $this->notifyEventDispatcher->dispatchNotification(
            $unit,
            'Zertifikat ausgelaufen',
            'Das SSL Zertifikat auf '.$unit->getDomain().' ist abgelaufen',
            UnitParameterBag::ALERT_RED
        );
    }

    /**
     * @param CertificateExpire $unit
     */
    private function processingCertificateValidAgain(CertificateExpire $unit)
    {
        $this->notifyEventDispatcher->dispatchNotification(
            $unit,
            'Zertifikat wieder OK',
            'Das SSL Zertifikat auf '.$unit->getDomain().' ist wieder in Ordnung.',
            UnitParameterBag::ALERT_GREEN
        );
    }

    /**
     * @param CertificateExpire $unit
     * @param int               $daysLeft
     */
    private function processingExpireSoon(CertificateExpire $unit, int $daysLeft): void
    {
        $this->notifyEventDispatcher->dispatchNotification(
            $unit,
            'Zertifikat läuft ab',
            'Das SSL Zertifikat auf '.$unit->getDomain().' läuft in '.$daysLeft.' Tage(n) aus.',
            UnitParameterBag::ALERT_YELLOW
        );
    }

}