#!/usr/bin/env bash

php -f docker/vhost.conf.php > /etc/apache2/sites-available/000-default.conf

chown -R www-data:www-data /var/www

mkdir -p /var/log/application && \
chown -R www-data:www-data /var/log/application && \
chmod -R 750 /var/log/application
mkdir -p /var/log/apache2 && \
touch /var/log/apache2/access.log && \
chown -R root:adm /var/log/apache2 && \
chmod -R 750 /var/log/apache2
touch /var/log/apache2/access.log

composer install
composer dump-autoload -o

service apache2 start

php /var/www/vendor/bin/phinx migrate
php /var/www/vendor/bin/phpunit

tail -f /var/log/apache2/access.log