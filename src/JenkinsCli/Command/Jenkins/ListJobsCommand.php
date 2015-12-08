<?php

namespace JenkinsCli\Command\Jenkins;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ListJobsCommand extends Command
{

    protected function configure()
    {
        $this
            ->setName('jenkins:list')
            ->setDescription('Observe build');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (is_readable(getcwd() . DIRECTORY_SEPARATOR . '.env.jenkins')) {
            $dotenv = new \Dotenv\Dotenv(getcwd(), '.env.jenkins');
            $dotenv->load();
        }

    }

}