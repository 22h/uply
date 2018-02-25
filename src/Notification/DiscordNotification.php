<?php

namespace App\Notification;

use App\Monitor\UnitParameterBag;
use App\Service\Discord\WebHookService;

/**
 * DiscordNotification
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
class DiscordNotification implements NotificationInterface
{

    /**
     * @var string
     */
    private $webHookUrl;

    /**
     * DiscordNotification constructor.
     *
     * @param string $defaultDiscordWebHook
     */
    public function __construct(string $defaultDiscordWebHook)
    {
        $this->webHookUrl = $defaultDiscordWebHook;
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return (!empty($this->webHookUrl));
    }

    /**
     * @param UnitParameterBag $monitorParameterBag
     *
     * @throws \Exception
     */
    public function send(UnitParameterBag $monitorParameterBag): void
    {
        (new WebHookService($this->webHookUrl))->sendByMonitoringParameterBag($monitorParameterBag);
    }
}