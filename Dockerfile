################## base_php ##################
FROM php:8.4-fpm-alpine3.22 AS base_php

# Set timezone
ENV TZ=Asia/Tehran
ARG WATCH_MODE=false

# PHP modules
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN chmod +x /usr/local/bin/install-php-extensions \
    && install-php-extensions \
    mysqli \
    pdo_mysql \
    exif \
    pcntl \
    bcmath \
    sockets \
    timezonedb \
    redis \
    @composer \
    gd \
    soap \
    zip \
    xsl \
    csv \
    openswoole \
    && rm -f /usr/local/bin/install-php-extensions

# OS dependencies
RUN apk update && apk add --no-cache \
    git \
    supervisor \
    tzdata \
    vim \
    wget \
    && cp /usr/share/zoneinfo/${TZ} /etc/localtime && echo ${TZ} > /etc/timezone \
    && rm -rf /var/cache/apk/*

RUN if [ "$WATCH_MODE" = "true" ]; then \
        apk add --no-cache nodejs npm \
        && npm install --save-dev chokidar \
        && rm -rf /var/cache/apk/*; \
    fi

################## php_nginx ##################
FROM base_php AS php_nginx

# Install and config Nginx
RUN apk update && apk add --no-cache \
    nginx \
    nginx-mod-http-vts \
    && rm -rf /var/cache/apk/* \
    # && chown -R www-data:www-data /var/lib/nginx \
    # && sed -i 's/user nginx;/user www-data;/' /etc/nginx/nginx.conf \
    && sed -i 's/client_header_buffer_size/# client_header_buffer_size/' /etc/nginx/nginx.conf \
    && sed -i '/^http {/a \    client_header_buffer_size 8k;' /etc/nginx/nginx.conf \
    && sed -i '/^http {/a \    vhost_traffic_status_zone;' /etc/nginx/nginx.conf \
    && sed -i '/^http {/a \    fastcgi_read_timeout 300;' /etc/nginx/nginx.conf \
    && sed -i '/^http {/a \    proxy_read_timeout 300;' /etc/nginx/nginx.conf \
    && sed -i 's/#gzip on;/gzip on;/' /etc/nginx/nginx.conf \
    && sed -i '/^http {/a \    log_format access '\''$remote_addr - $remote_user [$time_local] "$request" '\''\n                 '\''$status $body_bytes_sent "$http_referer" '\''\n                 '\''"$http_user_agent" "$http_x_forwarded_for" '\''\n                 '\''$request_time'\'';' /etc/nginx/nginx.conf \
    && sed -i 's/access_log \/var\/log\/nginx\/access.log main;/access_log \/var\/log\/nginx\/access.log access;/' /etc/nginx/nginx.conf

# update PHP-fpm configuration
# RUN touch /var/run/fpm.sock \
#     && chown www-data:www-data /var/run/fpm.sock \
#     && sed -i 's/listen/;listen/' /usr/local/etc/php-fpm.d/zz-docker.conf \
#     && echo 'listen = /var/run/fpm.sock' >> /usr/local/etc/php-fpm.d/zz-docker.conf \
#     && echo 'listen.owner = www-data' >> /usr/local/etc/php-fpm.d/zz-docker.conf \
#     && echo 'listen.group = www-data' >> /usr/local/etc/php-fpm.d/zz-docker.conf \
#     && echo 'listen.mode = 0660' >> /usr/local/etc/php-fpm.d/zz-docker.conf \
#     && echo 'pm.status_path = /php-fpm' >> /usr/local/etc/php-fpm.d/zz-docker.conf

# my Configs
RUN mv /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini
COPY .docker/config/php/web-prod.ini /usr/local/etc/php/conf.d/web-prod.ini.old
COPY .docker/config/php/subscriber-prod.ini /usr/local/etc/php/conf.d/subscriber-prod.ini.old
COPY .docker/config/php/scheduler-prod.ini /usr/local/etc/php/conf.d/scheduler-prod.ini.old
COPY .docker/config/nginx/dev.conf /etc/nginx/http.d/dev.conf.old
COPY .docker/config/nginx/prod.conf /etc/nginx/http.d/prod.conf.old
COPY .docker/config/nginx/vts.conf /etc/nginx/http.d/vts.conf

################## builder ##################
FROM php_nginx AS builder

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

COPY .docker/scripts/entrypoint.sh /opt/scripts/entrypoint.sh
COPY .docker/scripts/setup-jwt.sh /opt/scripts/setup-jwt.sh
COPY .docker/scripts/php-fpm.sh /opt/scripts/php-fpm.sh
COPY .docker/scripts/nginx.sh /opt/scripts/nginx.sh
COPY .docker/scripts/laravel.sh /opt/scripts/laravel.sh
COPY .docker/scripts/supervisord.sh /opt/scripts/supervisord.sh
COPY .docker/supervisor/dev.conf /opt/supervisor/dev.conf
COPY .docker/supervisor/web.conf /opt/supervisor/web.conf
COPY .docker/supervisor/subscriber.conf /opt/supervisor/subscriber.conf
COPY .docker/supervisor/scheduler.conf /opt/supervisor/scheduler.conf
COPY .docker/supervisor/all-in-one.conf /opt/supervisor/all-in-one.conf

RUN chmod +x /opt/scripts/*.sh

ENTRYPOINT ["/opt/scripts/entrypoint.sh"]
CMD ["--max_children=1", "--request_terminate_timeout=30s", "--supervisor_config=/opt/supervisor/all-in-one.conf", "--enable_ini=web-prod", "--nginx_conf=prod.conf"]
