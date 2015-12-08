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

class ObserveCommand extends Command
{

    protected function configure()
    {
        $this
            ->setName('jenkins:observe')
            ->setDescription('Observe build')
            ->addArgument(
                'build',
                InputArgument::OPTIONAL,
                'build'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $build = $input->getArgument('build');
        if (empty($build)) {
            $build = 'lastBuild';
        }

        if (is_readable(getcwd() . DIRECTORY_SEPARATOR . '.env.jenkins')) {
            $dotenv = new \Dotenv\Dotenv(getcwd(), '.env.jenkins');
            $dotenv->load();
        }


        $userId = getenv('JENKINS_USERID');
        $apiToken = getenv('JENKINS_APITOKEN');
        if (empty($userId)) {
            throw new \Exception('JENKINS_USERID not found');
        }
        if (empty($apiToken)) {
            throw new \Exception('JENKINS_APITOKEN not found');
        }

        $context = stream_context_create(array('http' => array('header'  => "Authorization: Basic " . base64_encode("$userId:$apiToken"))));

        $result = null;

        Poller::poll(
            function () use ($url, $context, $output, &$result) {
                $json = file_get_contents($url, false, $context);
                $result = json_decode($json, true);
                if ($result['building']) {
                    $output->write('.');
                }
                return (!$result['building']);
            },
            5,
            20
        );

        $output->writeln("\n". 'Build: '. $result['id']);
        $output->writeln('Status: '. $result['result']);

    }

}