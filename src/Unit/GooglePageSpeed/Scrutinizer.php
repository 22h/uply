<?php

namespace App\Unit\GooglePageSpeed;

use App\Entity\Unit\GooglePageSpeed;
use App\Monitor\Event\NotifyEventDispatcher;
use App\Monitor\UnitParameterBag;
use App\Service\Curl\CurlRequestFactory;
use App\Unit\ScrutinizerInterface;

/**
 * Scrutinizer
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
class Scrutinizer implements ScrutinizerInterface
{
    const STRATEGY_MOBILE  = 'mobile';
    const STRATEGY_DESKTOP = 'desktop';

    /**
     * @var NotifyEventDispatcher
     */
    private $notifyEventDispatcher;

    /**
     * @var CurlRequestFactory
     */
    private $curlRequestFactory;

    /**
     * @var string
     */
    private $apiKey;

    /**
     * Scrutinizer constructor.
     *
     * @param NotifyEventDispatcher $notifyEventDispatcher
     * @param CurlRequestFactory    $curlRequestFactory
     * @param string                $googlePageSpeedApiKey
     */
    public function __construct(
        NotifyEventDispatcher $notifyEventDispatcher,
        CurlRequestFactory $curlRequestFactory,
        string $googlePageSpeedApiKey
    ) {
        $this->notifyEventDispatcher = $notifyEventDispatcher;
        $this->curlRequestFactory = $curlRequestFactory;
        $this->apiKey = $googlePageSpeedApiKey;
    }

    /**
     * @param GooglePageSpeed $unit
     *
     * @throws \Exception
     */
    public function scrutinize($unit): void
    {
        $currentDesktop = $this->checkPageSpeed($unit->getUrl(), self::STRATEGY_DESKTOP);
        $currentMobile = $this->checkPageSpeed($unit->getUrl(), self::STRATEGY_MOBILE);
        $desktopFailed = ($currentDesktop < $unit->getLimitDesktop());
        $mobileFailed = ($currentMobile < $unit->getLimitMobile());
        $somethingFailed = ($desktopFailed || $mobileFailed);
        if ($somethingFailed && !$unit->isTriggered()) {
            $this->processSomeLimitFailedAndIsNotTriggeredBefore(
                $unit,
                $desktopFailed,
                $mobileFailed,
                $currentDesktop,
                $currentMobile
            );
        } elseif (!$somethingFailed && $unit->isTriggered()) {
            $this->processNoLimitFailedAndAlreadyTriggeredBefore($unit);
        }
    }

    /**
     * @param GooglePageSpeed $unit
     * @param bool            $desktopFailed
     * @param bool            $mobileFailed
     * @param int             $currentDesktop
     * @param int             $currentMobile
     */
    private function processSomeLimitFailedAndIsNotTriggeredBefore(
        GooglePageSpeed $unit,
        bool $desktopFailed,
        bool $mobileFailed,
        int $currentDesktop,
        int $currentMobile
    ): void {
        if ($desktopFailed && $mobileFailed) {
            $this->notifyEventDispatcher->dispatchNotification(
                $unit,
                'Der Google Page Speed Desktop und Mobile ist zu niedrig',
                'Der PageSpeed für Desktop soll min. '.$unit->getLimitDesktop().' betragen und beträgt '
                .$currentDesktop.', Mobil sollte er min. '.$unit->getLimitMobile(
                ).' betragen und beträgt '.$currentMobile.'.',
                UnitParameterBag::ALERT_RED
            );
        } elseif ($desktopFailed) {
            $this->notifyEventDispatcher->dispatchNotification(
                $unit,
                'Der Google Page Speed Desktop ist zu niedrig',
                'Der PageSpeed für Desktop soll min. '.$unit->getLimitDesktop().' betragen und beträgt '
                .$currentDesktop.'.',
                UnitParameterBag::ALERT_YELLOW
            );
        } elseif ($mobileFailed) {
            $this->notifyEventDispatcher->dispatchNotification(
                $unit,
                'Der Google Page Speed Mobile ist zu niedrig',
                'Der PageSpeed für Mobile soll min. '.$unit->getLimitMobile().' betragen und beträgt '
                .$currentMobile.'.',
                UnitParameterBag::ALERT_YELLOW
            );
        }
        if ($desktopFailed || $mobileFailed) {
            $unit->trigger();
        }
    }

    /**
     * @param GooglePageSpeed $unit
     */
    private function processNoLimitFailedAndAlreadyTriggeredBefore(GooglePageSpeed $unit): void
    {
        $this->notifyEventDispatcher->dispatchNotification(
            $unit,
            'Der Google Page Speed ist wieder richtig',
            'Der PageSpeed von ('.$unit->getUrl().') ist wieder hoch genug.',
            UnitParameterBag::ALERT_GREEN
        );
        $unit->removeTrigger();
    }

    /**
     * @param string $url
     * @param string $type
     *
     * @return int
     */
    private function checkPageSpeed(string $url, string $type): int
    {
        $curlRequest = $this->curlRequestFactory->createCurlRequest();
        $requestUrl = 'https://www.googleapis.com/pagespeedonline/v4/runPagespeed?url='.
            rawurlencode($url).'&strategy='.$type.'&key='.$this->apiKey;
        $curlRequest->request($requestUrl);
        if (is_null($curlRequest->getErrorCode())) {
            $response = json_decode($curlRequest->getResponse(), 1);
            if (isset($response['ruleGroups']['SPEED']['score'])) {
                return (int)$response['ruleGroups']['SPEED']['score'];
            }
        }

        return 0;
    }
}