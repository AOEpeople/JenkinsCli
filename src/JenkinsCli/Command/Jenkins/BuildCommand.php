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

class BuildCommand extends AbstractCommand
{

    protected function configure()
    {
        $this
            ->setName('jenkins:build')
            ->setDescription('Trigger build')
            ->addArgument(
                'job',
                InputArgument::REQUIRED,
                'job'
            )
            ->addOption(
                'parameter',
                'p',
                InputOption::VALUE_IS_ARRAY | InputOption::VALUE_OPTIONAL,
                'parameter (name=value)'
            );
    }

    protected function interact(InputInterface $input, OutputInterface $output) {
        return $this->interactAskForJob($input, $output);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $parameters = $input->getOption('parameter');
        $p = [];
        foreach ($parameters as $parameter) {
            list($name, $value) = explode('=', $parameter);
            $p[$name] = $value;
        }

        $job = $input->getArgument('job');
        $api = new Api();
        $res = $api->build($job, $p);
        var_dump($res);
    }

}