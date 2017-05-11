#!/bin/bash

set -e

cd /scripts

# run our setup which parses our env and checks for validity
php setup.php

echo "Cron start"
cron && tail -f /var/log/cron.log