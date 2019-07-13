<?php

namespace App\Notification\Service\Slack;

use App\Entity\Unit\UnitInterface;
use App\Notification\NotificationData;
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
     * @param string              $defaultSlackWebHook
     */
    public function __construct(GuzzleClientFactory $guzzleClientFactory, string $defaultSlackWebHook)
    {
        $this->webHookUrl = $defaultSlackWebHook;
        $this->guzzleClientFactory = $guzzleClientFactory;
    }

    /**
     * @param NotificationData $notificationData
     * @param UnitInterface    $unit
     *
     * @return bool
     * @throws \Exception
     */
    public function sendByMonitoringParameterBag(NotificationData $notificationData, UnitInterface $unit): bool
    {
        if (empty($this->webHookUrl)) {
            return false;
        }

        $color = '';
        if ($notificationData->isSuccess()) {
            $color = '1E824C';
        } elseif ($notificationData->isWarning()) {
            $color = 'F7CA18';
        } elseif ($notificationData->isDanger()) {
            $color = 'CF000F';
        } elseif ($notificationData->isError()) {
            $color = '663399';
        }

        $data = [
            'attachments' => [
                [
                    'title'  => $unit->getDomain(),
                    'footer' => date('d.m.Y. - H:i:s'),
                    'color'  => $color,
                    'text'   => $notificationData->getDescription(),
                ],
            ],
        ];

        return $this->send($data);
    }

    /**
     * @param array $data
     *
     * @return bool
     * @throws \Exception
     */
    private function send(array $data): bool
    {
        $client = $this->guzzleClientFactory->getNewGuzzleClient();
        $response = $client->post($this->webHookUrl, [RequestOptions::JSON => $data]);

        if ($response->getStatusCode() != 200) {
            throw new \Exception($response->getBody()->getContents());
        }

        return true;
    }
}