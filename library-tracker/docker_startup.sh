#!/bin/sh

chmod 777 -R /code/library-tracker/storage && chmod 777 -R /code/library-tracker/bootstrap/cache

# Startup Cron
cron

# Startup Supervisor
supervisord

# Startup PHP FPM daemon
/usr/local/sbin/php-fpm -c /usr/local/etc/php-fpm.conf
