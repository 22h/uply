<?php

namespace App\Notification;

use App\Monitor\UnitParameterBag;
use App\Service\Discord\WebHookService;

/**
 * DiscordNotification
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
class DiscordNotification
{

    /**
     * @var string
     */
    private $webHookUrl;

    /**
     * DiscordNotification constructor.
     *
     * @param string $webHookUrl
     */
    public function __construct(string $webHookUrl)
    {
        $this->webHookUrl = $webHookUrl;
    }

    /**
     * @param UnitParameterBag $monitorParameterBag
     *
     * @throws \Exception
     */
    public function send(UnitParameterBag $monitorParameterBag)
    {
        (new WebHookService($this->webHookUrl))->sendByMonitoringParameterBag($monitorParameterBag);
    }
}