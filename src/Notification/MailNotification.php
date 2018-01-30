<?php

namespace App\Notification;

use App\Monitor\UnitParameterBag;

/**
 * MailNotification
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
class MailNotification
{
    /**
     * @var \Swift_Mailer
     */
    private $swiftMailer;

    /**
     * @var string
     */
    private $mailFrom;

    /**
     * @var string
     */
    private $mailTo;

    /**
     * MailNotification constructor.
     *
     * @param \Swift_Mailer $swiftMailer
     * @param string        $mailFrom
     * @param string        $mailTo
     */
    public function __construct(\Swift_Mailer $swiftMailer, string $mailFrom, string $mailTo)
    {
        $this->swiftMailer = $swiftMailer;
        $this->mailFrom = $mailFrom;
        $this->mailTo = $mailTo;
    }

    /**
     * @param UnitParameterBag $monitorParameterBag
     */
    public function send(UnitParameterBag $monitorParameterBag)
    {
        $title = '[UPLY]['.$monitorParameterBag->getAlert().'] - '.$monitorParameterBag->getMonitor()->getDomain();

        $message = (new \Swift_Message($title))
            ->setFrom($this->mailFrom)
            ->setTo($this->mailTo)
            ->setBody($monitorParameterBag->getMessage());

        $this->swiftMailer->send($message);
    }
}