## How to use

The software was written enterily in php. It use php 7.4 and require composer.<br>
After downloading the repository, you can run the code directly from your machine, if you have installed php 7.4 (CLI) and composer; or you can use docker to get an environment suitable for executing the code.<br>
In the first case you need to download the software dependencies manually using the command:

`composer install`

In the second case it is necessary to build the docker image with the command:

`docker build -t sales_taxes_exercise .`

And then start a container with the command:

`docker run sales_taxes_exercise`

The default command of the image executes the test case that assert that my solution works against the supplied test data (view below for more details).
Otherwise you can run a console into the container using:

`docker run -it sales_taxes_exercise /bin/bash`

and then you can use all the commands below.


## Some preliminary explanations

I didn't want to use database to save data and to get product information from orders.
For this reason the problem arose of identifying whether a product was eligible for the **Basic sales tax** or not.

I thought (only for exercise purposes) to solve this problem using machine learning techniques, to "teach" the software to recognize the category of a product starting from its name.<br>
Obviously there were so many other possible solutions, but I wanted to have fun with this one.

And so I used RubixML in php to build a simple model, train it with test data (view `./ml/product_category/data/dataset.csv`), and then use it to guess the category of a product.<br>
I didn't want to get total accuracy, but only something acceptable, with a few lines of code.<br>
The model obtained easily passes the test with the data provided, but also with other data that I have tested.<br>



## Console command

I created 3 commands callable from the CLI. The command list can be obtained using:

`./src/console.php list`


### Order Receipt Command

`./src/console.php orderReceipt`

To test receipts creation. You can provide single or multiple order (view `--multiple` option).<br>
And you can save receipt to file (`--output=<file_name>`) or view it on the screen.<br>
The input data can be provided from a file (`--input==<file_name>`) or interactively if this option will not provided.<br>

Exemples of use:

`./src/console.php orderReceipt --input=tests/data/order_collection/input.txt --output=/root/receipt.txt --multiple`<br>
`./src/console.php orderReceipt --input=tests/data/order_collection/input.txt --multiple`


### Generate Model Command

`./src/console.php productCategory:generateModel`

To generate (trainig and save) machine learning model for product categories.


### Guess Category Command

`./src/console.php productCategory:guess <productName>`

To guess a category from a product name.<br>
Examples of use:

`./src/console.php productCategory:guess shoes`<br>
`./src/console.php productCategory:guess beer`<br>
`./src/console.php productCategory:guess chocolate`<br>
`./src/console.php productCategory:guess syringe`<br>


## Testing

For the exercise purpose you can use the command:

`./vendor/bin/phpunit tests/OrderCollectionTest.php`

to assert that my solution works against the supplied test data (look at `./tests/data/order_collection/input.txt` and `./tests/data/order_collection/output.txt` files to check the input and output data).

I have add another unit test, for validate the model used for guess categories from products names.
To test the entire testsuite, use:

`./vendor/bin/phpunit --testsuite sales_taxes_exercise`



## License

This project is licensed under the [MIT license](https://opensource.org/licenses/MIT).
