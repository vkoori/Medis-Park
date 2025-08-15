################## php_nginx ##################
FROM ghcr.io/vkoori/php_nginx:8.4-fpm-alpine3.22 AS base_image

RUN apk add --no-cache redis

################## builder ##################
FROM base_image AS builder

COPY ./composer.* .

RUN composer install --no-interaction --no-scripts --no-autoloader --prefer-dist --no-dev

COPY . /tmp/app

RUN chgrp -R 0 /tmp/app && \
    chmod -R g=u /tmp/app && \
    cp -a /tmp/app/. . && \
    rm -rf /tmp/app && \
    composer dump-autoload --classmap-authoritative && \
    chown www-data:www-data -R storage/ bootstrap/ && \
    echo "* * * * * php /var/www/html/artisan schedule:run >> /dev/null 2>&1" | crontab -

################## base ##################
FROM builder AS prod

RUN mv /usr/local/etc/php/conf.d/xx-php-ext-openswoole.ini /usr/local/etc/php/conf.d/xx-php-ext-openswoole.ini.old \
    && mv /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini

# my Configs
COPY .docker/config/php/web-prod.ini /usr/local/etc/php/conf.d/web-prod.ini.old
COPY .docker/config/php/subscriber-prod.ini /usr/local/etc/php/conf.d/subscriber-prod.ini.old
COPY .docker/config/php/scheduler-prod.ini /usr/local/etc/php/conf.d/scheduler-prod.ini.old
COPY .docker/config/nginx/fpm.conf /etc/nginx/http.d/fpm.conf.old
COPY .docker/config/nginx/octane.conf /etc/nginx/http.d/octane.conf.old
COPY .docker/config/nginx/vts.conf /etc/nginx/http.d/vts.conf
COPY .docker/scripts/entrypoint.sh /opt/scripts/entrypoint.sh
COPY .docker/scripts/setup-jwt.sh /opt/scripts/setup-jwt.sh
COPY .docker/scripts/php-fpm.sh /opt/scripts/php-fpm.sh
COPY .docker/scripts/nginx.sh /opt/scripts/nginx.sh
COPY .docker/scripts/laravel.sh /opt/scripts/laravel.sh
COPY .docker/scripts/supervisord.sh /opt/scripts/supervisord.sh
COPY .docker/supervisor/fpm.conf /opt/supervisor/fpm.conf
COPY .docker/supervisor/octane.conf /opt/supervisor/octane.conf
COPY .docker/supervisor/subscriber.conf /opt/supervisor/subscriber.conf
COPY .docker/supervisor/scheduler.conf /opt/supervisor/scheduler.conf
COPY .docker/supervisor/all-in-one.conf /opt/supervisor/all-in-one.conf

RUN chmod +x /opt/scripts/*.sh

ENTRYPOINT ["/opt/scripts/entrypoint.sh"]
CMD ["--max_children=5", "--request_terminate_timeout=30s", "--supervisor_config=/opt/supervisor/all-in-one.conf", "--enable_ini=web-prod", "--nginx_conf=fpm.conf"]
