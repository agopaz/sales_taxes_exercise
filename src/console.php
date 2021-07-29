#!/usr/bin/env php
<?php

require __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Console\Application;
use SalesTaxesExample\Command\OrderReceiptCommand;

// Application:
$application = new Application();

// Commands:
$application->add(new OrderReceiptCommand());

// Run tha console application:
$application->run();

