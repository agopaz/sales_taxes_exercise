<?php

namespace SalesTaxesExample\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Cursor;
use SalesTaxesExample\Entities\OrderItem;
use SalesTaxesExample\Entities\Product;
use SalesTaxesExample\Entities\Order;
use SalesTaxesExample\Entities\Collection\OrderCollection;


/**
 * Get an order and print the receipt.
 *
 * @author Agostino Pagnozzi
 */
class OrderReceiptCommand extends Command
{
    /**
     * Command name.
     *
     * @var string
     */
    protected static $defaultName = 'orderReceipt';


    protected function configure(): void
    {
        $this->setDescription('Print an order receipt.')
             ->setHelp('This command allows you to print the receipt of an order.')
             ->addOption('multiple', 'm', InputOption::VALUE_NONE, 'If present get an orders collection, otherwise a single order.')
             ->addOption('input', 'i', InputOption::VALUE_OPTIONAL, 'Input file. If not present ask for data interactively.')
             ->addOption('output', 'o', InputOption::VALUE_OPTIONAL, 'Output file. If not present print the result on the screen.');
    }


    /**
     * Std error.
     *
     * @var OutputInterface
     */
    protected $_stErr = null;


    /**
     * Initialize command before the execution.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        // Default std error to output:
        $this->_stdErr = $output;
        if ($output instanceof ConsoleOutputInterface) {
            // If it's available, get stdErr output:
            $this->_stdErr = $output->getErrorOutput();
        }

        // Redirect php error and warning to command error handler:
        set_error_handler(function($errno, $errstr, $errfile, $errline) use ($input, $output) {
            return $this->_fail($errstr);
        });
    }


    /**
     * Show error message and then exit with failure status code.
     *
     * @param string $failMessage
     *
     * @return int
     */
    protected function _fail(string $failMessage): int
    {
        $this->_stdErr->writeln('<error>' . $failMessage . '</error>');
        return Command::FAILURE;
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
        // Single order or collection of order:
        $multipleOrder = $input->getOption('multiple');

        // Input file:
        $inputFile = $input->getOption('input');

        // Output file:
        $outputFile = $input->getOption('output');

        // Output text:
        $outputText = "";

        // Check the input file:
        if (!empty($inputFile)) {
            if (!is_file($inputFile) || !is_readable($inputFile)) {
                return $this->_fail($inputFile . ': input file not valid!');
            }

            // Input text:
            $inputText = file_get_contents($inputFile);

            // Parse data:
            try {
                if ($multipleOrder) {
                    // Order collection:
                    $orderCollection = OrderCollection::fromString($inputText);
                    $outputText = $orderCollection->__toString();
                } else {
                    // Single order:
                    $order = Order::fromString($inputText);
                    $outputText = $order->__toString();
                }
            } catch (\InvalidArgumentException $ex) {
                return $this->_fail($ex->getMessage());
            }
        } else {
            if ($multipleOrder) {
                $orderCollection = $this->_askForOrderCollection($input, $output);
                $outputText = $orderCollection->__toString();
            } else {
                $order = $this->_askForOrder($input, $output);
                $outputText = $order->__toString();
            }
        }

        // Save to file:
        if (!empty($outputFile)) {
            file_put_contents($outputFile, $outputText);
        } else {
            // Output on the screen:
            $output->write($outputText, true);
        }

        return Command::SUCCESS;
    }


    protected function _clearAskedQuestion(OutputInterface $output): void
    {
        $cursor = new Cursor($output);
        $cursor->moveUp();
        $cursor->clearLine();
    }


    protected function _askForOrderCollection(InputInterface $input, OutputInterface $output): OrderCollection
    {
        $helper = $this->getHelper('question');

        // Number of orders:
        while (true) {
            $numOrdersQuestion = new Question('<comment>How many orders?</comment> ');
            $numOrder = $helper->ask($input, $output, $numOrdersQuestion);
            if ((string)intval($numOrder) == $numOrder && $numOrder > 0) {
                $numOrder = intval($numOrder);
                break;
            }

            $this->_clearAskedQuestion($output);
        }

        // Order collection:
        $orderCollection = new OrderCollection();

        // Collect order data:
        for ($i=1; $i<=$numOrder; $i++) {
            $output->writeln('');
            $output->writeln('<info>ORDER #' . $i . '</info>');

            $orderCollection[] = $this->_askForOrder($input, $output, $i);
        }

        return $orderCollection;
    }


    protected function _askForOrder(InputInterface $input, OutputInterface $output): Order
    {
        $helper = $this->getHelper('question');

        // Number of order items:
        while(true) {
            $numOrderItemsQuestion = new Question('<comment>How many items?</comment> ');
            $answer = $helper->ask($input, $output, $numOrderItemsQuestion);
            if ((string)intval($answer) == $answer && $answer > 0) {
                $numOrderItem = $answer;
                break;
            }

            $this->_clearAskedQuestion($output);
        }

        // Order:
        $order = new Order();

        // Collect order item data:
        for ($i=1; $i<=$numOrderItem; $i++) {
            $output->writeln('');
            $output->writeln('<info>Order item: #' . $i . '</info>');

            $order->addItem($this->_askForOrderItem($input, $output, $i));
        }

        return $order;
    }


    protected function _askForOrderItem(InputInterface $input, OutputInterface $output): OrderItem
    {
        $helper = $this->getHelper('question');

        // Quantity:
        while(true) {
            $qtyQuestion = new Question('<comment>Qty:</comment> ');
            $qty = $helper->ask($input, $output, $qtyQuestion);
            if ((string)intval($qty) == $qty && $qty > 0) {
                $qty = intval($qty);
                break;
            }

            $this->_clearAskedQuestion($output);
        }

        // Product:
        $product = $this->_askForProduct($input, $output);

        return new OrderItem($product, $qty);
    }


    protected function _askForProduct(InputInterface $input, OutputInterface $output): Product
    {
        $helper = $this->getHelper('question');

        // Name:
        while(true) {
            $nameQuestion = new Question('<comment>Name:</comment> ');
            $name = $helper->ask($input, $output, $nameQuestion);
            if (!empty($name)) {
                break;
            }

            $this->_clearAskedQuestion($output);
        }

        // Price:
        while(true) {
            $priceQuestion = new Question('<comment>Price:</comment> ');
            $price = $helper->ask($input, $output, $priceQuestion);
            if ((float)$price > 0) {
                $price = (float)$price;
                break;
            }

            $this->_clearAskedQuestion($output);
        }

        // Is imported:
        $isImportedQuestion = new ConfirmationQuestion(
            '<comment>Is imported? [yN]</comment> ',
            false
            );
        $isImported = $helper->ask($input, $output, $isImportedQuestion);


        // Package name:
        $packageNameQuestion = new Question('<comment>Package:</comment> ');
        $packageName = $helper->ask($input, $output, $packageNameQuestion);

        // Product:
        $product = new Product($name, $price, $isImported, $packageName);

        // Category:
        $output->writeln("<comment>Category guessed:</comment> " . $product->getCategory()->getName());

        return $product;
    }
}