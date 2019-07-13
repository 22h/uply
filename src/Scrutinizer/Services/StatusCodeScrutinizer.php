<?php

namespace App\Scrutinizer\Services;

use App\Entity\Unit\StatusCode;
use App\Entity\Unit\UnitInterface;
use App\Notification\NotificationData;
use App\Repository\Unit\StatusCodeRepository;
use App\Scrutinizer\AbstractScrutinizer;
use App\Service\HttpHeader;
use Exception;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * StatusCode
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
class StatusCodeScrutinizer extends AbstractScrutinizer
{
    /**
     * @var HttpHeader
     */
    private $httpHeader;

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * @param StatusCodeRepository  $repository
     * @param HttpHeader            $contentHashService
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(
        StatusCodeRepository $repository,
        HttpHeader $contentHashService,
        UrlGeneratorInterface $urlGenerator
    ) {
        parent::__construct($repository);

        $this->httpHeader = $contentHashService;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function scrutinize(UnitInterface $unit): NotificationData
    {
        if (!($unit instanceof StatusCode)) {
            throw new Exception('invalid unit');
        }
        $this->logger->info('status code scrutinizer');

        $notificationData = $this->checkStatuscode($unit->getId(), $unit->getUrl(), $unit->getStatusCode());
        $this->logger->info(
            sprintf(
                'check status code for (%s) first time and get level (%s)',
                $unit->getUrl(),
                $notificationData->getLevel()
            )
        );

        if (!$notificationData->isSuccess()) {
            sleep(2);
            $notificationData = $this->checkStatuscode($unit->getId(), $unit->getUrl(), $unit->getStatusCode());
            $this->logger->info(
                sprintf(
                    'checked status code for (%s) again and get level (%s)',
                    $unit->getUrl(),
                    $notificationData->getLevel()
                )
            );
        }

        return $notificationData;
    }

    /**
     * @param int    $id
     * @param string $url
     * @param int    $expectedStatusCode
     *
     * @return NotificationData
     */
    private function checkStatusCode(int $id, string $url, int $expectedStatusCode): NotificationData
    {
        try {
            $actualStatusCode = $this->httpHeader->requestStatusCode($url);
        } catch (Exception $exception) {
            return $this->notificationDataFactory->createErrorNotificationData(
                'status_code.error',
                ['%url%' => $url, '%exception%' => $exception->getMessage()]
            );
        }

        if ($actualStatusCode === $expectedStatusCode) {
            return $this->notificationDataFactory->createSuccessNotificationData(
                'status_code.success',
                [
                    '%url%'                => $url,
                    '%expectedStatusCode%' => $expectedStatusCode,
                ]
            );
        } else {
            return $this->notificationDataFactory->createDangerNotificationData(
                'status_code.danger',
                [
                    '%url%'                => $url,
                    '%actualStatusCode%'   => $actualStatusCode,
                    '%expectedStatusCode%' => $expectedStatusCode,
                    '%refreshUrl%'         => $this->generateRefreshUrl($id),
                ]
            );
        }
    }

    /**
     * @param int $id
     *
     * @return string
     */
    private function generateRefreshUrl(int $id): string
    {
        return $this->urlGenerator->generate(
            'command_status_code_refresh',
            ['id' => $id],
            UrlGeneratorInterface::ABSOLUTE_URL
        );
    }
}