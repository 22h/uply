<?php

namespace App\Monitor\Unit;

use App\Entity\Unit\GooglePageSpeed;
use App\Entity\Unit\UnitInterface;
use App\Monitor\UnitParameterBag;
use App\Monitor\UnitParameterBagFactory;
use App\Repository\Unit\GooglePageSpeedRepository;
use App\Service\CurlRequest;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * GooglePageSpeedUnit
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
class GooglePageSpeedUnit extends AbstractUnitCheck
{

    const STRATEGY_MOBILE  = 'mobile';
    const STRATEGY_DESKTOP = 'desktop';

    /**
     * @var GooglePageSpeedRepository
     */
    protected $repository;

    /**
     * @var CurlRequest
     */
    private $curlRequest;

    /**
     * @var string
     */
    private $apiKey;

    /**
     * GooglePageSpeedUnit constructor.
     *
     * @param UnitParameterBagFactory   $parameterBagFactory
     * @param EventDispatcherInterface  $eventDispatcher
     * @param GooglePageSpeedRepository $repository
     * @param CurlRequest               $curlRequest
     * @param string                    $apiKey
     */
    public function __construct(
        UnitParameterBagFactory $parameterBagFactory,
        EventDispatcherInterface $eventDispatcher,
        GooglePageSpeedRepository $repository,
        CurlRequest $curlRequest,
        string $apiKey
    ) {
        $this->repository = $repository;
        $this->curlRequest = $curlRequest;
        $this->apiKey = $apiKey;

        parent::__construct($parameterBagFactory, $eventDispatcher);
    }

    /**
     * @param UnitInterface $unit
     *
     * @throws \Exception
     */
    public function handle(UnitInterface $unit): void
    {
        parent::handle($unit);
        /** @var GooglePageSpeed $unit */

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
            $this->triggerNotification(
                $unit,
                'Der Google Page Speed Desktop und Mobile ist zu niedrig',
                'Der PageSpeed für Desktop soll min. '.$unit->getLimitDesktop().' betragen und beträgt '
                .$currentDesktop.', Mobil sollte er min. '.$unit->getLimitMobile(
                ).' betragen und beträgt '.$currentMobile.'.',
                UnitParameterBag::ALERT_RED
            );
        } elseif ($desktopFailed) {
            $this->triggerNotification(
                $unit,
                'Der Google Page Speed Desktop ist zu niedrig',
                'Der PageSpeed für Desktop soll min. '.$unit->getLimitDesktop().' betragen und beträgt '
                .$currentDesktop.'.',
                UnitParameterBag::ALERT_YELLOW
            );
        } elseif ($mobileFailed) {
            $this->triggerNotification(
                $unit,
                'Der Google Page Speed Mobile ist zu niedrig',
                'Der PageSpeed für Mobile soll min. '.$unit->getLimitMobile().' betragen und beträgt '
                .$currentMobile.'.',
                UnitParameterBag::ALERT_YELLOW
            );
        }

        if ($desktopFailed || $mobileFailed) {
            $this->repository->setAsTriggered($unit->getId());
        }
    }

    /**
     * @param GooglePageSpeed $unit
     */
    private function processNoLimitFailedAndAlreadyTriggeredBefore(GooglePageSpeed $unit): void
    {
        $this->triggerNotification(
            $unit,
            'Der Google Page Speed ist wieder richtig',
            'Der PageSpeed von ('.$unit->getUrl().') ist wieder hoch genug.',
            UnitParameterBag::ALERT_GREEN
        );
        $this->repository->removeTriggered($unit->getId());
    }

    /**
     * @return string
     */
    public function entityClass(): string
    {
        return GooglePageSpeed::class;
    }

    /**
     * @param string $url
     * @param string $type
     *
     * @return int
     */
    private function checkPageSpeed(string $url, string $type): int
    {
        $requestUrl = 'https://www.googleapis.com/pagespeedonline/v4/runPagespeed?url='.
            rawurlencode($url).'&strategy='.$type.'&key='.$this->apiKey;

        $this->curlRequest->request($requestUrl);

        if (is_null($this->curlRequest->getErrorCode())) {
            $response = json_decode($this->curlRequest->getResponse(), 1);

            if (isset($response['ruleGroups']['SPEED']['score'])) {
                return (int)$response['ruleGroups']['SPEED']['score'];
            }
        }

        return 0;
    }
}