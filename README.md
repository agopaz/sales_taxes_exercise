## How to use

The software was written enterily in php. It use php 7.4 and require composer and mongodb.<br>
After downloading the repository, you can run the code directly from your machine; or you can use docker to get an environment suitable for executing the code.<br>
In the first case you need to download the software dependencies manually using the command:

`composer install`

and create a new mongodb database named `sales_taxes`, with a collection named `products`. We need to import into this collection the content of `./mongo/products.json` file with the command:

`mongoimport --db=sales_taxes --collection=product ./mongo/products.json`

In the second case it is necessary to build the docker image with the command:

`docker build -t sales_taxes_exercise .`

And then start a container with the command:

`docker run sales_taxes_exercise`

The default command of the image executes the test case that assert that my solution works against the supplied test data (view below for more details).
Otherwise you can run a console into the container using:

`docker run -it --entrypoint bash sales_taxes_exercise`

and then you can use all the commands below.


## Mongodb

I use a mongo database to store same information about products. In particular to store product category in order to identify the products that are exempt from paying the **Basic sales tax**.


## Console command

I created a command callable from the CLI to test receipts creation.

`./src/console.php orderReceipt`

You can provide single or multiple order (view `--multiple` option).<br>
And you can save receipt to file (`--output=<file_name>`) or view it on the screen.<br>
The input data can be provided from a file (`--input==<file_name>`) or interactively if this option will not provided.<br>

Exemples of use:

`./src/console.php orderReceipt --input=tests/data/order_collection/input.txt --output=/root/receipt.txt --multiple`<br>
`./src/console.php orderReceipt --input=tests/data/order_collection/input.txt --multiple`


## Testing

For the exercise purpose you can use the command:

`./vendor/bin/phpunit tests/OrderCollectionTest.php`

or the command:

`./vendor/bin/phpunit --testsuite sales_taxes_exercise`



## License

This project is licensed under the [MIT license](https://opensource.org/licenses/MIT).
