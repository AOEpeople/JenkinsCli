<?php

if (is_readable(getcwd() . DIRECTORY_SEPARATOR . '.env.jenkins')) {
    $dotenv = new Dotenv\Dotenv(getcwd(), '.env.jenkins');
    $dotenv->load();
}