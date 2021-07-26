## Appunti
Non ho voluto usare framework specifici per implementare l'esercizio.
Non essendo richiesto non ho voluto aggiungere il supporto al salvataggio dei model in un database.
In un caso reale lo implementerei diversamente, qui l'item dell'ordine ha relazione diretta con un prodotto, ma in un caso reale con prodotti e ordini salvati su un databse sarebbe necessario replicare nell'item dell'ordine tutte quelle informazioni che devono essere persistite e che devono restare sull'ordine anche quando il prodotto correlato viene modificato (es. nome prodotto, prezzo di effettivo acquisto, ecc...)


## Testing

`./vendor/bin/phpunit tests/OrderCollectionTest.php`
`./vendor/bin/phpunit --testsuite sales_taxes_exercise`


## Console command

`./src/console.php order-receipt --input=tests/data/order_collection/input.txt --output=/root/receipt.txt`
`./src/console.php order-receipt --input=tests/data/order_collection/input.txt`


## License

This project is licensed under the [MIT license](https://opensource.org/licenses/MIT).