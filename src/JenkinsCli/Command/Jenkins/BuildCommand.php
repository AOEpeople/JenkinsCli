<?php

namespace JenkinsCli\Command\Jenkins;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class BuildCommand extends Command
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
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $job = $input->getArgument('job');

        $branch = $input->getArgument('branch');
        $userId = getenv('JENKINS_USERID');
        $apiToken = getenv('JENKINS_APITOKEN');
        if (empty($userId)) {
            throw new \Exception('JENKINS_USERID not found');
        }
        if (empty($apiToken)) {
            throw new \Exception('JENKINS_APITOKEN not found');
        }
        if (empty($url)) {
            throw new \Exception('JENKINS_URL not found');
        }

        $url .= "$job/build/";

        //$data = ['parameter' => [
        //    ['name' => 'BRANCH_TO_BUILD', 'value' => $branch],
        //    ['name' => 'skipBuildGrunt', 'value' => '1']
        //]];

        $ch = curl_init();

        // $query = "json='".json_encode($data)."'";
        // $command = "curl -k -X POST $url --user $userId:$apiToken --data-urlencode $query";
        // echo $command;

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERPWD, "$userId:$apiToken");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER , true);
        // curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POSTFIELDS, array('json' => json_encode($data)));
        $response = curl_exec($ch);
        curl_close($ch);

        if (strpos($response, 'HTTP/1.1 201 Created') !== false) {
            $output->writeln('Job triggered successfully');
        } else {
            $output->writeln($response);
        }
    }

}