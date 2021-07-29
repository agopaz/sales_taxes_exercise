FROM php:7.4-cli

COPY . /usr/src/sales_taxes_exercise
WORKDIR /usr/src/sales_taxes_exercise

# Install same package:
RUN apt update && apt install -y zip wget gnupg procps

# Mongo APT key and repository:
RUN wget -qO - https://www.mongodb.org/static/pgp/server-5.0.asc | apt-key add -
RUN echo "deb http://repo.mongodb.org/apt/debian buster/mongodb-org/5.0 main" | tee /etc/apt/sources.list.d/mongodb-org-5.0.list

# Install mongodb package:
RUN apt update && apt install -y mongodb-org

# Install mongo module:
RUN pecl install mongodb && docker-php-ext-enable mongodb

# Install composer:
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Download project dependencies with composer:
RUN composer install

ENTRYPOINT [ "./entrypoint.sh" ]
CMD [ "./vendor/bin/phpunit", "tests/OrderCollectionTest.php" ];

#CMD [ "./vendor/bin/phpunit", "tests/OrderCollectionTest.php" ]
