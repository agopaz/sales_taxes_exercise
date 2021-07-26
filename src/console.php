#!/usr/bin/env php
<?php

require __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Console\Application;
use SalesTaxesExample\Command\OrderReceiptCommand;
use SalesTaxesExample\Command\ProductCategory;

// Application:
$application = new Application();

// Commands:
$application->add(new OrderReceiptCommand());
$application->add(new ProductCategory\GuessCommand());
$application->add(new ProductCategory\GenerateModelCommand());

// Run tha console application:
$application->run();

