<?php

namespace App\Notification;

use Symfony\Component\Translation\TranslatorInterface;

/**
 * NotificationDataFactory
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
class NotificationDataFactory
{

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @param string $languageKey
     * @param array  $languageParameters
     *
     * @return NotificationData
     */
    public function createSuccessNotificationData(
        string $languageKey,
        array $languageParameters
    ): NotificationData {
        return $this->createNewInstance($languageKey, $languageParameters, NotificationData::LEVEL_SUCCESS);
    }

    /**
     * @param string $languageKey
     * @param array  $languageParameters
     *
     * @return NotificationData
     */
    public function createWarningNotificationData(
        string $languageKey,
        array $languageParameters
    ): NotificationData {
        return $this->createNewInstance($languageKey, $languageParameters, NotificationData::LEVEL_WARNING);
    }

    /**
     * @param string $languageKey
     * @param array  $languageParameters
     *
     * @return NotificationData
     */
    public function createDangerNotificationData(
        string $languageKey,
        array $languageParameters
    ): NotificationData {
        return $this->createNewInstance($languageKey, $languageParameters, NotificationData::LEVEL_DANGER);
    }

    /**
     * @param string $languageKey
     * @param array  $languageParameters
     *
     * @return NotificationData
     */
    public function createErrorNotificationData(
        string $languageKey,
        array $languageParameters
    ): NotificationData {
        return $this->createNewInstance($languageKey, $languageParameters, NotificationData::LEVEL_ERROR);
    }

    /**
     * @param string $languageKey
     * @param array  $languageParameters
     * @param string $level
     *
     * @return NotificationData
     */
    private function createNewInstance(string $languageKey, array $languageParameters, string $level)
    {
        $description = $this->translator->trans(
            $languageKey,
            $languageParameters,
            'notification'
        );

        return new NotificationData($description, $level);
    }
}