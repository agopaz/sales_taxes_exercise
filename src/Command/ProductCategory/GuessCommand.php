<?php

namespace SalesTaxesExample\Command\ProductCategory;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Rubix\ML\PersistentModel;
use Rubix\ML\Persisters\Filesystem;
use Rubix\ML\Datasets\Unlabeled;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Guess a category from a product name.
 *
 * @author Agostino Pagnozzi
 */
class GuessCommand extends Command
{
    /**
     * Command name.
     *
     * @var string
     */
    protected static $defaultName = 'productCategory:guess';


    protected function configure(): void
    {
        $this->setDescription('Guess a category from a product name.')
             ->setHelp('This command allows you to test the ML model used to guess a category from a product name.')
             ->addArgument('productName', InputArgument::REQUIRED, 'Name of the product.');
    }

    /**
     * Execute the command.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     *@return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $estimator = PersistentModel::load(new Filesystem(__DIR__ . '/../../../ml/product_category/data/model.rbx'));

        // Product name:
        $productName = $input->getArgument('productName');

        // Dataset:
        $dataset = new Unlabeled([ $productName ]);

        // Prediction:
        $prediction = current($estimator->predict($dataset));

        $output->writeln("<info>Guessed category: </info> " . $prediction);

        return Command::SUCCESS;
    }
}