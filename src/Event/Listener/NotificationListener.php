<?php

namespace App\Event\Listener;

use App\Monitor\Event\MonitorNotifyEvent;
use App\Notification\DiscordNotification;
use App\Notification\MailNotification;

/**
 * NotificationListener
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
class NotificationListener
{
    /**
     * @var MailNotification
     */
    private $mailNotification;

    /**
     * @var DiscordNotification
     */
    private $discordNotification;

    /**
     * NotificationListener constructor.
     *
     * @param MailNotification    $mailNotification
     * @param DiscordNotification $discordNotification
     */
    public function __construct(MailNotification $mailNotification, DiscordNotification $discordNotification)
    {
        $this->mailNotification = $mailNotification;
        $this->discordNotification = $discordNotification;
    }

    /**
     * @param MonitorNotifyEvent $event
     */
    public function onMonitorNotify(MonitorNotifyEvent $event): void
    {
        if($this->mailNotification->isEnabled()) {
            $this->mailNotification->send($event->getParameterBag());
        }
        if($this->discordNotification->isEnabled()) {
            $this->discordNotification->send($event->getParameterBag());
        }
    }
}