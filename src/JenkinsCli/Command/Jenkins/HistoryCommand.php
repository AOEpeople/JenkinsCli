<?php

namespace JenkinsCli\Command\Jenkins;

use JenkinsCli\Api;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;

class HistoryCommand extends AbstractCommand
{

    protected function configure()
    {
        $this
            ->setName('jenkins:history')
            ->setDescription('Job history')
            ->addArgument(
                'job',
                InputArgument::REQUIRED,
                'job'
            );
    }

    protected function interact(InputInterface $input, OutputInterface $output) {
        return $this->interactAskForJob($input, $output);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $job = $input->getArgument('job');

        $api = new Api();
        $res = $api->history($job);

        var_dump($res);
    }

}