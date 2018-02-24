<?php

namespace App\Command;

use App\Entity\Unit\UnitInterface;
use App\Monitor\Event\MonitorFinishedEvent;
use App\Monitor\MonitorUnitChain;
use App\Monitor\Unit\UnitCheckInterface;
use App\Monitor\UnitNotFoundException;
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
     * @var MonitorUnitChain
     */
    private $unitChain;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * MonitorCommand constructor.
     *
     * @param MonitorUnitChain         $unitChain
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(MonitorUnitChain $unitChain, EventDispatcherInterface $eventDispatcher)
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
        $monitorId = (int)$input->getArgument('id');
        $monitorType = (string)$input->getArgument('type');

        try {
            $monitor = $this->unitChain->getMonitoringClassByIdent($monitorType);
            $monitor = $this->getContainer()->get($monitor);
        } catch (\Exception $exception) {
            $monitor = null;
            $this->logger->error('there is no monitoring registered with name '.$monitorType.'.');
        }

        if ($monitor instanceof UnitCheckInterface) {
            try {
                $unit = $monitor->getUnit($monitorId);
            } catch (UnitNotFoundException $notFoundException) {
                $this->logger->error(
                    'can not find an monitor unit with id: '.$monitorId.' and class '.$monitorType.'.'
                );
                exit(1);
            }
            $monitor->handle($unit);
            $this->dispatchedMonitorFinished($unit);
        }
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