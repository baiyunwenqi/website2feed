#!/usr/bin/env php
<?php

if (php_sapi_name() !== "cli") {
    http_response_code(404);
}

require 'vendor/autoload.php';

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class RunCrawlerCommand extends Command {

    protected function configure() {
        $this
                ->setName('run')
                ->setDescription('Runs the crawler to parse the specified websites')
                ->addOption(
                        'nomail',
                        null,
                        InputOption::VALUE_NONE,
                        'Do not mail errors')
                ->addOption(
                        'skip-errors',
                        null,
                        InputOption::VALUE_NONE,
                        'If crawling a feed fails, skips the feed instead of terminating. Only has effect if --all is specified')
                ->addOption(
                        'dry',
                        null,
                        InputOption::VALUE_NONE,
                        'Do not write results to the database, display results on the console')
                ->addOption(
                        'all',
                        null,
                        InputOption::VALUE_NONE,
                        'Crawl all feeds in the database')
                ->addOption(
                        'single',
                        null,
                        InputOption::VALUE_REQUIRED,
                        'Crawl the feed (specified by ID) only');
        
        // Run crawler here
    }
    
    protected function execute(InputInterface $input, OutputInterface $output) {
        $nomail = $input->getOption('nomail');
        $skipErrors = $input->getOption('skip-errors');
        $dry = $input->getOption('dry');
        $all = $input->getOption('all');
        $single = $input->getOption('single');
        
        if ($all && $single) {
            throw new RuntimeException('Cannot set --all and --single together.');
        }
        if (!$all && !$single) {
            throw new RuntimeException('Specify either --all or --single.');
        }
        
        if (!$dry) {
            throw new RuntimeException('Non-dry runs are disabled for now.');
        }
    }
}

$application = new Application();
$application->add(new RunCrawlerCommand());
$application->run();