<?php

namespace App\Notification\Event\Listener;

use App\Notification\Event\NotificationSendEvent;
use App\Notification\Service\Slack\WebHookService;

/**
 * SendSlackNotificationListener
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
class SendSlackNotificationListener
{
    /**
     * @var WebHookService
     */
    private $webHookService;

    /**
     * SendDiscordNotificationListener constructor.
     *
     * @param WebHookService $webHookService
     */
    public function __construct(WebHookService $webHookService)
    {
        $this->webHookService = $webHookService;
    }

    /**
     * @param NotificationSendEvent $event
     *
     * @throws \Exception
     */
    public function onNotificationSend(NotificationSendEvent $event): void
    {
        $this->webHookService->sendByMonitoringParameterBag($event->getNotificationData(), $event->getUnit());
    }
}