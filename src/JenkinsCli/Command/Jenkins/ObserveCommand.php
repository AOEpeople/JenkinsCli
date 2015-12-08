<?php

namespace JenkinsCli\Command\Jenkins;

use StackFormation\Poller;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ObserveCommand extends AbstractCommand
{

    protected function configure()
    {
        $this
            ->setName('jenkins:observe')
            ->setDescription('Observe build')
            ->addArgument(
                'job',
                InputArgument::REQUIRED,
                'job'
            )
            ->addArgument(
                'build',
                InputArgument::REQUIRED,
                'build'
            );
    }

    protected function interact(InputInterface $input, OutputInterface $output) {
         $this->interactAskForJob($input, $output);
        // TODO: get history for given job (if build is not set)
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        throw new \Exception('Not implemented yet');
    }


}