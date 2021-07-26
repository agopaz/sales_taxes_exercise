<?php

namespace SalesTaxesExample\Command\ProductCategory;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Rubix\ML\PersistentModel;
use Rubix\ML\Persisters\Filesystem;
use Rubix\ML\Datasets\Labeled;
use Rubix\ML\Extractors\CSV;
use Rubix\ML\Pipeline;
use Rubix\ML\Transformers\WordCountVectorizer;
use Rubix\ML\Transformers\MultibyteTextNormalizer;
use Rubix\ML\Tokenizers\WordStemmer;
use Rubix\ML\Classifiers\KDNeighbors;

/**
 * Guess a category from a product name.
 *
 * @author Agostino Pagnozzi
 */
class GenerateModelCommand extends Command
{
    /**
     * Command name.
     *
     * @var string
     */
    protected static $defaultName = 'productCategory:generateModel';


    protected function configure(): void
    {
        $this->setDescription('Train and save a model to predict category from a product name.')
             ->setHelp('This command allows you to train and save the ML model used to predict category from a product name.');
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
        // Dataset:
        $dataset = Labeled::fromIterator(new CSV(__DIR__ . '/../../../ml/product_category/data/dataset.csv'));

        // Estimator:
        $estimator = new PersistentModel(
            new Pipeline(
                [
                    new MultibyteTextNormalizer(),
                    new WordCountVectorizer(1500, 0, 1, new WordStemmer('english')),
                ],

                new KDNeighbors(1)
            ),
            new Filesystem(__DIR__ . '/../../../ml/product_category/data/model.rbx', true)
        );

        $output->writeln("<info>Train model...</info>");

        // Train the estimator:
        $estimator->train($dataset);

        $output->writeln("<info>Model trained</info>");

        // Save the model:
        $estimator->save();

        $output->writeln("<info>Model saved</info>");

        return Command::SUCCESS;
    }
}