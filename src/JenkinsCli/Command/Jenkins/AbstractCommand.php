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

abstract class AbstractCommand extends Command
{

    protected function interactAskForJob(InputInterface $input, OutputInterface $output)
    {
        $job = $input->getArgument('job');
        if (empty($job)) {
            $api = new Api();

            $helper = $this->getHelper('question');
            $question = new ChoiceQuestion('Please select a job', $api->getAllJobs());

            $question->setErrorMessage('Job %s is invalid.');

            $job = $helper->ask($input, $output, $question);
            $output->writeln('Selected Job: ' . $job);

            list($stackName) = explode(' ', $job);
            $input->setArgument('job', $stackName);
        }
        return $job;
    }

}