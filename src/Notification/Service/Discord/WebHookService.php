<?php

namespace App\Notification\Service\Discord;

use App\Entity\Unit\UnitInterface;
use App\Notification\NotificationData;
use App\Notification\Service\Discord\WebHook\Embed;
use App\Notification\Service\Discord\WebHook\WebHook;
use App\Service\GuzzleClientFactory;
use GuzzleHttp\RequestOptions;

/**
 * WebHookService
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
class WebHookService
{
    /**
     * @var string|null
     */
    private $webHookUrl = null;
    /**
     * @var GuzzleClientFactory
     */
    private $guzzleClientFactory;

    /**
     * WebHookService constructor.
     *
     * @param GuzzleClientFactory $guzzleClientFactory
     * @param string              $defaultDiscordWebHook
     */
    public function __construct(GuzzleClientFactory $guzzleClientFactory, string $defaultDiscordWebHook)
    {
        $this->webHookUrl = $defaultDiscordWebHook;
        $this->guzzleClientFactory = $guzzleClientFactory;
    }

    /**
     * @param NotificationData $notificationData
     * @param UnitInterface    $unit
     *
     * @return bool
     */
    public function sendByMonitoringParameterBag(NotificationData $notificationData, UnitInterface $unit): bool
    {
        if (empty($this->webHookUrl)) {
            return false;
        }

        $embed = (new Embed())
            ->setTitle($unit->getDomain())
            ->setDescription($notificationData->getDescription())
            ->setFooter((new Embed\Footer())->setText(date('d.m.Y. - H:i:s')));

        if ($notificationData->isSuccess()) {
            $embed->setColor('1E824C');
        } elseif ($notificationData->isWarning()) {
            $embed->setColor('F7CA18');
        } elseif ($notificationData->isDanger()) {
            $embed->setColor('CF000F');
        } elseif ($notificationData->isError()) {
            $embed->setColor('663399');
        }

        return $this->send((new WebHook())->addEmbed($embed));
    }

    /**
     * @param WebHook $webHook
     *
     * @return bool
     * @throws \Exception
     */
    private function send(WebHook $webHook): bool
    {
        $client = $this->guzzleClientFactory->getNewGuzzleClient();
        $response = $client->post($this->webHookUrl, [RequestOptions::JSON => $webHook->returnArray()]);

        if ($response->getStatusCode() != 204) {
            throw new \Exception($response->getBody()->getContents());
        }

        return true;
    }
}