<?php

namespace App\Notification;

/**
 * NotificationData
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
class NotificationData
{

    const LEVEL_SUCCESS = 'success';
    const LEVEL_WARNING = 'warning';
    const LEVEL_DANGER  = 'danger';
    const LEVEL_ERROR   = 'error';

    const LEVELS = [
        self::LEVEL_SUCCESS,
        self::LEVEL_WARNING,
        self::LEVEL_DANGER,
        self::LEVEL_ERROR,
    ];

    /**
     * @var string
     */
    private $level;

    /**
     * @var string
     */
    private $description;

    /**
     * @param string $description
     * @param string $level
     *
     * @throws \Exception
     */
    public function __construct(string $description, string $level)
    {
        $this->description = $description;


        if (!$this->isLevelValid($level)) {
            throw new \Exception(sprintf('level %s is not valid', $level));
        }
        $this->level = $level;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getLevel(): string
    {
        return $this->level;
    }

    /**
     * @return bool
     */
    public function isSuccess(): bool
    {
        return self::LEVEL_SUCCESS === $this->getLevel();
    }

    /**
     * @return bool
     */
    public function isWarning(): bool
    {
        return self::LEVEL_WARNING === $this->getLevel();
    }

    /**
     * @return bool
     */
    public function isDanger(): bool
    {
        return self::LEVEL_DANGER === $this->getLevel();
    }

    /**
     * @return bool
     */
    public function isError(): bool
    {
        return self::LEVEL_ERROR === $this->getLevel();
    }

    /**
     * @param string $level
     *
     * @return bool
     */
    private function isLevelValid(string $level)
    {
        return in_array($level, self::LEVELS);
    }
}