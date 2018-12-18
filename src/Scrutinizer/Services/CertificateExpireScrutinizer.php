<?php

namespace App\Scrutinizer\Services;

use App\Entity\Unit\CertificateExpire;
use App\Entity\Unit\UnitInterface;
use App\Notification\NotificationData;
use App\Repository\Unit\CertificateExpireRepository;
use App\Scrutinizer\AbstractScrutinizer;
use Spatie\SslCertificate\SslCertificate;

/**
 * CertificateExpire
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
class CertificateExpireScrutinizer extends AbstractScrutinizer
{

    /**
     * @param CertificateExpireRepository $repository
     */
    public function __construct(CertificateExpireRepository $repository)
    {
        parent::__construct($repository);
    }

    /**
     * @inheritDoc
     */
    public function scrutinize(UnitInterface $unit): NotificationData
    {
        if (!($unit instanceof CertificateExpire)) {
            throw new \Exception('invalid unit');
        }

        return $this->checkCertificate($unit->getDomain(), $unit->getRemindBefore());
    }

    /**
     * @param string $domain
     * @param int    $remindBefore
     *
     * @return NotificationData
     */
    private function checkCertificate(string $domain, int $remindBefore): NotificationData
    {
        try {
            $certificate = SslCertificate::createForHostName($domain);
        } catch (\Exception $exception) {
            return $this->notificationDataFactory->createErrorNotificationData(
                'certificate.error',
                ['%domain%' => $domain, '%exception%' => $exception->getMessage()]
            );
        }

        $daysLeft = $certificate->expirationDate()->diffInDays();
        $expireSoon = ($remindBefore >= $daysLeft);
        if ($certificate->isExpired()) {
            return $this->notificationDataFactory->createDangerNotificationData(
                'certificate.danger',
                ['%domain%' => $domain]
            );
        } elseif ($expireSoon) {
            return $this->notificationDataFactory->createWarningNotificationData(
                'certificate.warning',
                ['%domain%' => $domain, '%days%' => $daysLeft]
            );
        } else {
            return $this->notificationDataFactory->createSuccessNotificationData(
                'certificate.success',
                ['%domain%' => $domain, '%days%' => $daysLeft]
            );
        }
    }
}