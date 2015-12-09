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

class InfoCommand extends AbstractCommand
{

    protected function configure()
    {
        $this
            ->setName('jenkins:info')
            ->setDescription('Info about the last build')
            ->addArgument(
                'job',
                InputArgument::REQUIRED,
                'job'
            )
            ->addArgument(
                'build',
                InputArgument::OPTIONAL,
                'build'
            );
    }

    protected function interact(InputInterface $input, OutputInterface $output) {
        return $this->interactAskForJob($input, $output);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $job = $input->getArgument('job');
        $build = $input->getArgument('build') ? $input->getArgument('build') : 'lastBuild';
        $api = new Api();
        $res = $api->info($job, $build);

        $rows=[];

        $parameters = [];
        if (isset($res['actions'][0]['parameters']) && is_array($res['actions'][0]['parameters'])) {
            foreach ($res['actions'][0]['parameters'] as $parameter) {
                $parameters[] = implode('=', $parameter);
            }
            $rows[] = ['Parameters', implode("\n", $parameters)];
        }

        foreach($res as $key => $value) {
            if (is_scalar($value) || is_null($value)) {
                $rows[] = [$key, $value];
            } else {
                $rows[] = [$key, substr(json_encode($value), 0, 100).'...'];
            }
        }

        $table = new Table($output);
        $table
            ->setRows($rows);
        $table->render();
    }

}