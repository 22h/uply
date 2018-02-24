<?php

namespace App\Service\Discord;

use App\Monitor\UnitParameterBag;
use App\Service\Discord\WebHook\Embed;
use App\Service\Discord\WebHook\WebHook;

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
     * @param string $webHookUrl
     */
    public function __construct(string $webHookUrl)
    {
        $this->webHookUrl = $webHookUrl;
    }

    /**
     * @param UnitParameterBag $monitorParameterBag
     *
     * @return bool
     * @throws \Exception
     */
    public function sendByMonitoringParameterBag(UnitParameterBag $monitorParameterBag): bool
    {
        $embed = (new Embed())
            ->setTitle($monitorParameterBag->getMonitor()->getDomain())
            ->setDescription($monitorParameterBag->getMessage())
            ->setFooter((new Embed\Footer())->setText(date('d.m.Y. - H:i:s')));

        if (!is_null($monitorParameterBag->getAlert())) {
            switch ($monitorParameterBag->getAlert()) {
                case UnitParameterBag::ALERT_GREEN:
                    $embed->setColor('1E824C');
                    break;
                case UnitParameterBag::ALERT_YELLOW:
                    $embed->setColor('F7CA18');
                    break;
                case UnitParameterBag::ALERT_RED:
                    $embed->setColor('CF000F');
                    break;
            }
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
        $output = json_decode($output, true);

        if (curl_getinfo($curl, CURLINFO_HTTP_CODE) != 204) {
            throw new \Exception($output['message']);
        }

        curl_close($curl);

        return true;
    }
}
