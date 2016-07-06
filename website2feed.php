#!/usr/bin/env php
<?php

if (php_sapi_name() !== "cli") {
    http_response_code(404);
}

require 'vendor/autoload.php';
require 'generated-conf/config.php';
$config = require 'config.local.php';

ini_set('user_agent', 'Website2Feed Test Bot (level14.hu)');

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Level14\Website2Feed\Model\FeedQuery;


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

        $feeds = [];
        if ($single) {
            $feeds = FeedQuery::create()->findById($single);
        }
        else {
            $feeds = FeedQuery::create()->find();
        }
        
        foreach ($feeds as $feed) {
            try {
                $newItems = $feed->queryItems();
                $oldCount = $feed->countItems();
                $newCount = count($newItems);
                $newDateSet = false;
                for ($i = $newCount - 1; $i >= $oldCount; $i--) {
                    $feed->addItem($newItems[$i]);
                    if (!$newDateSet) {
                        $feed->setUpdatedDate($newItems[$i]->getPublishedDate());
                        $newDateSet = true;
                    }
                    print "Adding " . $newItems[$i]->getTitle() . "\n";
                }

                if (!$dry) {
                    print "Saving " . $feed->getTitle() . "\n";
                    $feed->save();
                }
                else {
                    print "Dry run, no saving\n";
                }
            } catch (Exception $ex) {
                if ($skipErrors) {
                    print "Error: " . $ex->getMessage() . "\n";
                }
                else {
                    throw $ex;
                }
            }
        }
    }
}

$application = new Application();
$application->add(new RunCrawlerCommand());
$application->run();
