#!/bin/bash

# Start mongod daemon:
/usr/bin/mongod --config=/etc/mongod.conf &>/dev/null &

# Import data in db:
/usr/bin/mongoimport --db=sales_taxes --collection=products /usr/src/sales_taxes_exercise/mongo/products.json --drop

# Execute passed command:
exec "$@"