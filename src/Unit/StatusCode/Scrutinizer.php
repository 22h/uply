<?php

namespace App\Unit\StatusCode;

use App\Entity\Unit\StatusCode;
use App\Monitor\Event\NotifyEventDispatcher;
use App\Monitor\UnitParameterBag;
use App\Service\HttpHeader;
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
     * @var HttpHeader
     */
    private $httpHeader;

    /**
     * Scrutinizer constructor.
     *
     * @param NotifyEventDispatcher $notifyEventDispatcher
     * @param HttpHeader            $httpHeader
     */
    public function __construct(NotifyEventDispatcher $notifyEventDispatcher, HttpHeader $httpHeader)
    {
        $this->notifyEventDispatcher = $notifyEventDispatcher;
        $this->httpHeader = $httpHeader;
    }

    /**
     * @param StatusCode $unit
     *
     * @throws \Exception
     */
    public function scrutinize($unit): void
    {
        // todo: bust cache!
        $receivedStatusCode = $this->httpHeader->requestStatusCode($unit->getUrl());
        if ($receivedStatusCode === $unit->getStatusCode()) {
            $this->processStatusCodeIsAsExpected($unit);
        } elseif ($receivedStatusCode >= 100 && $receivedStatusCode <= 999) {
            $this->processStatusCodeIsUnexpected($unit, $receivedStatusCode);
        } else {
            $this->processError($unit);
        }
    }

    /**
     * @param StatusCode $unit
     */
    private function processStatusCodeIsAsExpected(StatusCode $unit): void
    {
        if ($unit->isTriggered()) {

            $this->notifyEventDispatcher->dispatchNotification(
                $unit,
                'Status Code ist wieder richtig',
                'Der Status Code von ('.$unit->getUrl().') hat wieder auf  '
                .$unit->getStatusCode().' zurück gewechselt.',
                UnitParameterBag::ALERT_GREEN
            );

            $unit->removeTrigger();
        }
    }

    /**
     * @param StatusCode $unit
     * @param int        $receivedStatusCode
     */
    private function processStatusCodeIsUnexpected(StatusCode $unit, int $receivedStatusCode): void
    {
        if (!$unit->isTriggered()) {
            $this->notifyEventDispatcher->dispatchNotification(
                $unit,
                'Status Code ist falsch',
                'Der Status Code von ('.$unit->getUrl().') ist '.$receivedStatusCode
                .' müsste aber '.$unit->getStatusCode().' sein.',
                UnitParameterBag::ALERT_RED
            );
            $unit->trigger();
        }
    }

    /**
     * @param StatusCode $unit
     */
    private function processError(StatusCode $unit): void
    {
        $this->notifyEventDispatcher->dispatchNotification(
            $unit,
            'Status Code nicht abrufbar',
            'Der Status Code von ('.$unit->getUrl().') konnte nicht geprüft werden '.
            'da die Verbindung fehlgeschlagen ist.',
            UnitParameterBag::ALERT_RED
        );
        $unit->trigger();
    }
}