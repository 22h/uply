<?php

namespace App\Event\Listener;

use App\Monitor\Event\MonitorNotifyEvent;
use App\Monitor\UnitParameterBag;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

/**
 * NotificationLoggingListener
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
class NotificationLoggingListener implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @param MonitorNotifyEvent $event
     *
     * @throws \Exception
     */
    public function onMonitorNotify(MonitorNotifyEvent $event): void
    {
        $parameterBag = $event->getParameterBag();

        $logMessage = $parameterBag->getUnit()->getDomain().': '.$parameterBag->getMessage();

        switch ($event->getParameterBag()->getAlert()) {
            case UnitParameterBag::ALERT_RED:
                $this->logger->error($logMessage);
                $this->logger->error(serialize($parameterBag));
                break;
            case UnitParameterBag::ALERT_YELLOW:
                $this->logger->warning($logMessage);
                $this->logger->warning(serialize($parameterBag));
                break;
            case UnitParameterBag::ALERT_GREEN:
                $this->logger->notice($logMessage);
                $this->logger->notice(serialize($parameterBag));
                break;
            default:
                $this->logger->info($logMessage);
                $this->logger->info(serialize($parameterBag));
        }
    }
}