<?php

namespace JenkinsCli;


class CommandRegistry {

    public static function getCommands() {
        return [
            new \JenkinsCli\Command\Jenkins\BuildCommand(),
            new \JenkinsCli\Command\Jenkins\ObserveCommand(),
            new \JenkinsCli\Command\Jenkins\ListJobsCommand()
        ];
    }

}