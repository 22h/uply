<?php

namespace App\Command;

use App\Entity\Event;
use App\Repository\EventRepository;
use App\Service\EventFactory;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * StopCommand
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
class StopCommand extends ContainerAwareCommand
{

    const COMMAND_NAME = 'monitor:stop';

    /**
     * @var EventRepository
     */
    protected $eventRepository;

    /**
     * @var EventFactory
     */
    private $eventFactory;

    /**
     * StopCommand constructor.
     *
     * @param EventRepository $eventRepository
     * @param EventFactory    $eventFactory
     */
    public function __construct(EventRepository $eventRepository, EventFactory $eventFactory)
    {
        $this->eventRepository = $eventRepository;
        $this->eventFactory = $eventFactory;

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
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $symfonyStyle = new SymfonyStyle($input, $output);

        $event = $this->eventFactory->buildEvent(
            (new \DateTime('2000-01-01')),
            0,
            Event::UNIT_SPECIAL_TYPE_EXIT
        );

        try {
            $this->eventRepository->save($event);
            $symfonyStyle->success('loop is now stopped');
        } catch (ORMException $exception) {
            $symfonyStyle->error('can not create stop/exit event');
        }
    }
}