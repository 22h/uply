<?php

namespace App\Command;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * CheckLoopCommand
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
class CheckLoopCommand extends ContainerAwareCommand implements LoggerAwareInterface
{

    use LoggerAwareTrait;

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName('monitor:loop:check');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($this->countProcesses() === 0) {
            $this->logger->info('restart '.LoopCommand::COMMAND_NAME.' now');
            shell_exec('php bin/console '.LoopCommand::COMMAND_NAME.' > /dev/null 2>/dev/null &');
        }
    }

    /**
     * @return int
     */
    protected function countProcesses(): int
    {
        $lines = [];

        exec('ps -ef | grep "bin/console '.LoopCommand::COMMAND_NAME.'"', $lines);

        $count = 0;
        foreach ($lines as $line) {
            if (strpos($line, 'php bin/console '.LoopCommand::COMMAND_NAME) !== false) {
                $count++;
            }
        }

        return $count;
    }
}
