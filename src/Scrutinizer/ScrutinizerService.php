<?php

namespace App\Scrutinizer;

use App\Entity\Job;
use App\Entity\Unit\UnitInterface;
use App\Notification\Event\NotificationSendEvent;
use App\Notification\NotificationData;
use App\Scrutinizer\Exception\UnitDeactivatedException;
use App\Scrutinizer\Exception\UnitNotFoundException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * ScrutinizerService
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
class ScrutinizerService
{
    /**
     * @var ScrutinizerChain
     */
    private $scrutinizerChain;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @param ScrutinizerChain         $scrutinizerChain
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(
        ScrutinizerChain $scrutinizerChain,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->scrutinizerChain = $scrutinizerChain;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param Job $job
     *
     * @return \DateTime
     * @throws \Exception
     */
    public function scrutinizeJob(Job $job): \DateTime
    {
        $scrutinizer = $this->scrutinizerChain->getScrutinizer($job->getUnitIdent());
        $unit = $scrutinizer->getRepository()->find($job->getUnitId());
        if (!($unit instanceof UnitInterface)) {
            throw new UnitNotFoundException(
                sprintf(
                    'no unit with id (%d) and ident (%s) in combination found',
                    $job->getId(),
                    $job->getUnitIdent()
                )
            );
        }
        if ($unit->isDeactivated()) {
            throw new UnitDeactivatedException();
        }

        $notificationData = $scrutinizer->scrutinize($unit);

        if ($unit->getActualLevel() !== $notificationData->getLevel()) {
            $this->triggerNotificationEvent($notificationData, $unit);
        }

        $unit->setActualLevel($notificationData->getLevel());
        $scrutinizer->getRepository()->save($unit);

        if ($notificationData->isSuccess() || $notificationData->isWarning()) {
            $nextCheck = new \DateTime(sprintf('+%d minutes', $unit->getIdleTime()));
        } else {
            $nextCheck = new \DateTime(sprintf('+%d minutes', $unit->getTriggeredIdleTime()));
        }

        return $nextCheck;
    }

    /**
     * @param NotificationData $notificationData
     * @param UnitInterface    $unit
     */
    private function triggerNotificationEvent(NotificationData $notificationData, UnitInterface $unit): void
    {
        $this->eventDispatcher->dispatch(
            NotificationSendEvent::EVENT_NAME,
            new NotificationSendEvent($notificationData, $unit)
        );
    }
}