<?php

namespace App\Monitor;

use App\Entity\Unit\UnitInterface;

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
     * @var UnitInterface
     */
    private $unit;

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
     * @param UnitInterface $monitor
     * @param string        $message
     * @param string        $title
     * @param null|string   $alert
     */
    public function __construct(
        UnitInterface $monitor,
        string $message,
        string $title,
        ?string $alert = null
    ) {
        $this->unit = $monitor;
        $this->message = $message;
        $this->title = $title;
        $this->alert = $alert;
    }

    /**
     * @return UnitInterface
     */
    public function getUnit(): UnitInterface
    {
        return $this->unit;
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