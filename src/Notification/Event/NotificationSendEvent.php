<?php

namespace App\Notification\Event;

use App\Entity\Unit\UnitInterface;
use App\Notification\NotificationData;
use Symfony\Component\EventDispatcher\Event;

/**
 * NotifyEvent
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
class NotificationSendEvent extends Event
{

    const EVENT_NAME = 'uply.notification.send';

    /**
     * @var NotificationData
     */
    private $notificationData;

    /**
     * @var UnitInterface
     */
    private $unit;

    /**
     * @param NotificationData $notificationData
     * @param UnitInterface    $unit
     */
    public function __construct(NotificationData $notificationData, UnitInterface $unit)
    {
        $this->notificationData = $notificationData;
        $this->unit = $unit;
    }

    /**
     * @return NotificationData
     */
    public function getNotificationData(): NotificationData
    {
        return $this->notificationData;
    }

    /**
     * @return UnitInterface
     */
    public function getUnit(): UnitInterface
    {
        return $this->unit;
    }
}