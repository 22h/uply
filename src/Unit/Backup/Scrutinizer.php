<?php

namespace App\Unit\Backup;

use App\Entity\Unit\Backup;
use App\Monitor\Event\NotifyEventDispatcher;
use App\Monitor\UnitParameterBag;
use App\Unit\ScrutinizerInterface;

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
     * @param Backup $unit
     */
    public function scrutinize($unit): void
    {
        $json = file_get_contents($unit->getUrl());
        if ($json === false) {
            $this->notifyEventDispatcher->dispatchNotification(
                $unit,
                'Die Verbindung ist Fehlgeschlagen',
                'Das Backup konnte nicht überprüft werden, da keine Verbindung zum Server'
                .' aufgenommen werden konnte.',
                UnitParameterBag::ALERT_RED
            );
            $unit->trigger();
            return;
        }

        $content = json_decode($json);

        $startDate = strtotime($content->start_date);
        $endDate = strtotime($content->end_date);

        if(round((time() - $startDate) / 60) > 1440) {
            $this->notifyEventDispatcher->dispatchNotification(
                $unit,
                'Es wurde kein Backup gemacht',
                'In den letzten 24 Stunden wurde kein Backup erstellt',
                UnitParameterBag::ALERT_RED
            );
            $unit->trigger();

            return;
        }

        $minutes = round(($endDate - $startDate) / 60);

        $timeFailed = ($unit->getLimitTime() < $minutes);
        $sizeFailed = ($unit->getLimitSize() < (int)$content->size);

        $somethingFailed = ($timeFailed || $sizeFailed);
        if ($somethingFailed) {
            $this->processSomeLimitFailedAndIsNotTriggeredBefore(
                $unit,
                $timeFailed,
                $sizeFailed,
                $minutes,
                (int)$content->size
            );
            $unit->trigger();
        } elseif (!$somethingFailed && $unit->isTriggered()) {
            $this->processNoLimitFailedAndAlreadyTriggeredBefore($unit);
            $unit->removeTrigger();
        }
    }

    /**
     * @param Backup $unit
     * @param bool            $timeFailed
     * @param bool            $sizeFailed
     * @param int             $currentMinutes
     * @param int             $currentSize
     */
    private function processSomeLimitFailedAndIsNotTriggeredBefore(
        Backup $unit,
        bool $timeFailed,
        bool $sizeFailed,
        int $currentMinutes,
        int $currentSize
    ): void {
        if ($timeFailed && $sizeFailed) {
            $this->notifyEventDispatcher->dispatchNotification(
                $unit,
                'Das Backup brauchte zu lang und war zu groß',
                'Das Backup darf maximal '.$unit->getLimitTime().' Minuten brauchen und hat '.$currentMinutes
                .' Minuten benötigt. Zudem war das Backup zu groß und hat '.$currentSize.' MB gebraucht, das Limit '
                .'liegt aber bei '.$unit->getLimitSize().' MB',
                UnitParameterBag::ALERT_RED
            );
        } elseif ($timeFailed) {
            $this->notifyEventDispatcher->dispatchNotification(
                $unit,
                'Das Backup hat zu lange benötigt',
                'Das Backup darf maximal '.$unit->getLimitTime().' Minuten brauchen und hat '.$currentMinutes
                .' Minuten benötigt.',
                UnitParameterBag::ALERT_YELLOW
            );
        } elseif ($sizeFailed) {
            $this->notifyEventDispatcher->dispatchNotification(
                $unit,
                'Das Backup ist zu groß',
                'Das Backup ist '.$currentSize.' MB groß und darf nur '.$unit->getLimitSize().' MB betragen.',
                UnitParameterBag::ALERT_YELLOW
            );
        }
    }

    /**
     * @param Backup $unit
     */
    private function processNoLimitFailedAndAlreadyTriggeredBefore(Backup $unit): void
    {
        $this->notifyEventDispatcher->dispatchNotification(
            $unit,
            'Das Backup ist wieder in Ordnung',
            'Das Backup hat wieder die Limits unterschritten',
            UnitParameterBag::ALERT_GREEN
        );
    }
}