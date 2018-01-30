<?php

namespace App\Monitor\Unit;

use App\Entity\Unit\StatusCode;
use App\Entity\Unit\UnitInterface;
use App\Monitor\UnitParameterBag;
use App\Monitor\UnitParameterBagFactory;
use App\Repository\Unit\StatusCodeRepository;
use App\Service\HttpHeader;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * StatusCodeUnit
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
class StatusCodeUnit extends AbstractUnitCheck
{

    /**
     * @var StatusCodeRepository
     */
    protected $repository;

    /**
     * @var HttpHeader
     */
    private $httpHeader;

    /**
     * StatusCodeUnit constructor.
     *
     * @param UnitParameterBagFactory  $parameterBagFactory
     * @param EventDispatcherInterface $eventDispatcher
     * @param StatusCodeRepository     $repository
     * @param HttpHeader               $httpHeader
     */
    public function __construct(
        UnitParameterBagFactory $parameterBagFactory,
        EventDispatcherInterface $eventDispatcher,
        StatusCodeRepository $repository,
        HttpHeader $httpHeader
    ) {
        $this->repository = $repository;
        $this->httpHeader = $httpHeader;

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
        /** @var StatusCode $unit */

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
            $this->triggerNotification(
                $unit,
                'Status Code ist wieder richtig',
                'Der Status Code von ('.$unit->getUrl().') hat wieder auf  '
                .$unit->getStatusCode().' zurück gewechselt.',
                UnitParameterBag::ALERT_GREEN
            );
            $this->repository->removeTriggered($unit->getId());
        }
    }

    /**
     * @param StatusCode $unit
     * @param int        $receivedStatusCode
     */
    private function processStatusCodeIsUnexpected(StatusCode $unit, int $receivedStatusCode): void
    {
        if (!$unit->isTriggered()) {
            $this->triggerNotification(
                $unit,
                'Status Code ist falsch',
                'Der Status Code von ('.$unit->getUrl().') ist '.$receivedStatusCode
                .' müsste aber '.$unit->getStatusCode().' sein.',
                UnitParameterBag::ALERT_RED
            );
            $this->repository->setAsTriggered($unit->getId());
        }
    }

    /**
     * @param StatusCode $unit
     */
    private function processError(StatusCode $unit): void
    {
        $this->triggerNotification(
            $unit,
            'Status Code nicht abrufbar',
            'Der Status Code von ('.$unit->getUrl().') konnte nicht geprüft werden '.
            'da die Verbindung fehlgeschlagen ist.',
            UnitParameterBag::ALERT_RED
        );
        $this->repository->setAsTriggered($unit->getId());
    }

    /**
     * @return string
     */
    public function entityClass(): string
    {
        return StatusCode::class;
    }
}