<?php

namespace App\Notification\Service\Slack;

use App\Entity\Unit\UnitInterface;
use App\Notification\NotificationData;

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
     * WebHookService constructor.
     *
     * @param string $defaultSlackWebHook
     */
    public function __construct(?string $defaultSlackWebHook)
    {
        $this->webHookUrl = $defaultSlackWebHook;
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
        $data_string = json_encode($data);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->webHookUrl);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, ['payload' => $data_string]);
        $output = curl_exec($curl);
        if (curl_getinfo($curl, CURLINFO_HTTP_CODE) != 200) {
            throw new \Exception($output);
        }
        curl_close($curl);

        return true;
    }
}