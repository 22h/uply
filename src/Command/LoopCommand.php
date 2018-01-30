<?php

namespace App\Command;

use App\Entity\Event;
use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * LoopCommand
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
class LoopCommand extends ContainerAwareCommand
{

    const COMMAND_NAME = 'monitor:loop';

    /**
     * @var EventRepository
     */
    protected $eventRepository;

    /**
     * LoopCommand constructor.
     *
     * @param EventRepository $eventRepository
     */
    public function __construct(EventRepository $eventRepository)
    {
        $this->eventRepository = $eventRepository;

        parent::__construct();
    }

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName(self::COMMAND_NAME);
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int|null|void
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        while (true) {
            $event = $this->eventRepository->findNextUnit();

            if ($event instanceof Event) {
                $this->cycle($event);
            }
            sleep(1);
        }
    }

    /**
     * @param Event $event
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function exitEvent(Event $event): void
    {
        if ($event->isExitEvent()) {
            $this->eventRepository->remove($event);
            exit(0);
        }
    }

    /**
     * @param Event $event
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function sleepEvent(Event $event): void
    {
        if ($event->isSleepEvent()) {
            $this->eventRepository->remove($event);
            sleep(30);
        }
    }

    /**
     * @param Event $event
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function cycle(Event $event): void
    {
        $this->exitEvent($event);
        $this->sleepEvent($event);

        $unitId = $event->getUnitId();
        $unitType = $event->getUnitIdent();

        $this->eventRepository->remove($event);
        shell_exec('php bin/console monitor:monitor '.$unitId.' '.$unitType.' > /dev/null 2>/dev/null &');
    }
}