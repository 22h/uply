<?php

namespace App\Scrutinizer;

use App\Notification\NotificationDataFactory;
use App\Repository\Unit\AbstractUnitRepository;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

/**
 * AbstractScrutinizer
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
abstract class AbstractScrutinizer implements ScrutinizerInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @var AbstractUnitRepository
     */
    protected $repository;

    /**
     * @var NotificationDataFactory
     */
    protected $notificationDataFactory;

    /**
     * @param AbstractUnitRepository $repository
     */
    public function __construct(AbstractUnitRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @inheritDoc
     */
    public function getRepository(): AbstractUnitRepository
    {
        return $this->repository;
    }

    /**
     * @inheritDoc
     */
    public function getIdent(): string
    {
        return (string)call_user_func($this->getEntityClass().'::getIdent');
    }

    /**
     * @inheritDoc
     */
    public function getEntityClass(): string
    {
        return $this->repository->getClassName();
    }

    /**
     * @param NotificationDataFactory $notificationDataFactory
     */
    public function setNotificationDataFactory(NotificationDataFactory $notificationDataFactory)
    {
        $this->notificationDataFactory = $notificationDataFactory;
    }
}