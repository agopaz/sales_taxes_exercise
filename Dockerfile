FROM php:7.4-cli

COPY . /usr/src/sales_taxes_exercise
WORKDIR /usr/src/sales_taxes_exercise

RUN apt update && apt install -y zip
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install

CMD [ "./vendor/bin/phpunit", "tests/OrderCollectionTest.php" ]