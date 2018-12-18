<?php

namespace App\Notification\Service\Discord;

use App\Entity\Unit\UnitInterface;
use App\Notification\NotificationData;
use App\Notification\Service\Discord\WebHook\Embed;
use App\Notification\Service\Discord\WebHook\WebHook;

/**
 * WebHookService
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
class WebHookService
{
    /**
     * @var string
     */
    private $webHookUrl = null;

    /**
     * WebHookService constructor.
     *
     * @param string $defaultDiscordWebHook
     */
    public function __construct(string $defaultDiscordWebHook)
    {
        $this->webHookUrl = $defaultDiscordWebHook;
    }

    /**
     * @param NotificationData $notificationData
     * @param UnitInterface    $unit
     *
     * @return bool
     */
    public function sendByMonitoringParameterBag(NotificationData $notificationData, UnitInterface $unit): bool
    {
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
        $data_string = json_encode($webHook->returnArray());
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->webHookUrl);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        $output = curl_exec($curl);
        if (curl_getinfo($curl, CURLINFO_HTTP_CODE) != 204) {
            throw new \Exception($output);
        }
        curl_close($curl);

        return true;
    }
}