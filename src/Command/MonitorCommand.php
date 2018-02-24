<?php

namespace App\Command;

use App\Entity\Unit\UnitInterface;
use App\Monitor\Event\MonitorFinishedEvent;
use App\Monitor\UnitServiceChain;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * MonitorCommand
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
class MonitorCommand extends ContainerAwareCommand implements LoggerAwareInterface
{

    use LoggerAwareTrait;

    /**
     * @var UnitServiceChain
     */
    private $unitChain;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * MonitorCommand constructor.
     *
     * @param UnitServiceChain         $unitChain
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(UnitServiceChain $unitChain, EventDispatcherInterface $eventDispatcher)
    {
        $this->unitChain = $unitChain;
        $this->eventDispatcher = $eventDispatcher;

        parent::__construct();
    }

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName('monitor:monitor')
            ->addArgument('id', InputArgument::REQUIRED, 'id of the monitor entity')
            ->addArgument('type', InputArgument::REQUIRED, 'type of monitor entity');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $unitId = (int)$input->getArgument('id');
        $unitIdent = (string)$input->getArgument('type');

        try {
            $monitor = $this->unitChain->getUnitService($unitIdent);
        } catch (\Exception $exception) {
            $this->logger->error('there is no monitoring registered with name '.$unitIdent.'.');

            return;
        }

        $unit = $monitor->scrutinize($unitId);
        $this->dispatchedMonitorFinished($unit);
    }

    /**
     * dispatchedMonitorFinished
     *
     * @param UnitInterface $unit
     */
    private function dispatchedMonitorFinished(UnitInterface $unit): void
    {
        $event = new MonitorFinishedEvent($unit);
        $this->eventDispatcher->dispatch(MonitorFinishedEvent::NAME, $event);
    }
}