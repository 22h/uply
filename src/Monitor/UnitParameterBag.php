<?php

namespace App\Monitor;

use App\Entity\Unit\AbstractUnit;

/**
 * MonitorParameterBag
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
class UnitParameterBag
{

    const ALERT_GREEN  = 'green';
    const ALERT_YELLOW = 'yellow';
    const ALERT_RED    = 'red';

    /**
     * @var AbstractUnit
     */
    private $monitor;

    /**
     * @var string
     */
    private $message;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $alert;

    /**
     * MonitorParameterBag constructor.
     *
     * @param AbstractUnit $monitor
     * @param string       $message
     * @param string       $title
     * @param null|string  $alert
     */
    public function __construct(
        AbstractUnit $monitor,
        string $message,
        string $title,
        ?string $alert = null
    ) {
        $this->monitor = $monitor;
        $this->message = $message;
        $this->title = $title;
        $this->alert = $alert;
    }

    /**
     * @return AbstractUnit
     */
    public function getMonitor(): AbstractUnit
    {
        return $this->monitor;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getAlert(): string
    {
        return $this->alert;
    }
}