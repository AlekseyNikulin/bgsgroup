FROM debian:stretch-slim
MAINTAINER Aleksey Nikulin <masterweb@e1.ru>

ENV TZ=Europe/Moscow
ENV PHP_MODS_DIR=/etc/php/7.4/mods-available
ENV PHP_CLI_DIR=/etc/php/7.4/cli
ENV PHP_CLI_CONF_DIR=${PHP_CLI_DIR}/conf.d
ENV PHP_FPM_DIR=/etc/php/7.4/fpm
ENV PHP_FPM_CONF_DIR=${PHP_FPM_DIR}/conf.d
ENV PHP_FPM_POOL_DIR=${PHP_FPM_DIR}/pool.d

RUN \
    apt-get update -y && \
    apt-get upgrade -y && \
    apt-get install -y \
        wget \
        curl \
        apt-transport-https \
        ca-certificates \
        lsb-release \
        net-tools \
        gnupg2 \
        atop \
        git \
        unzip \
        locales \
        locales-all && \
    # date and timezone
    echo ${TZ} > /etc/timezone && \
    cp -f /usr/share/zoneinfo/${TZ} /etc/localtime && \
    date; \
    # locale
    echo ru_RU.UTF-8 UTF-8 >> /etc/locale.gen && \
    locale-gen && \
    update-locale && \
    # php
    wget -O /etc/apt/trusted.gpg.d/php.gpg https://packages.sury.org/php/apt.gpg && \
    echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" > /etc/apt/sources.list.d/php7.4.list && \
    apt-get update && apt-get upgrade -y && \
    apt-get install -y \
        php7.4 \
        php7.4-cli \
        php7.4-fpm \
        php7.4-apc \
        php7.4-apcu \
        php7.4-bz2 \
        php7.4-bcmath \
        php7.4-calendar \
        php7.4-readline \
        php7.4-ctype \
        php7.4-curl \
        php7.4-geoip \
        php7.4-gettext \
        php7.4-gd \
        php7.4-intl \
        php7.4-imagick \
        php7.4-imap \
        php7.4-ldap \
        php7.4-mbstring \
        php7.4-memcached \
        php7.4-mongo \
        php7.4-mysql \
        php7.4-pdo \
        php7.4-pgsql \
        php7.4-redis \
        php7.4-soap \
        php7.4-sqlite3 \
        php7.4-zip \
        php7.4-xmlrpc \
        php7.4-xsl && \
    # composer
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    composer global require "hirak/prestissimo:^0.3" && \
    # php mod(s)
    ln -s ${PHP_MODS_DIR}/custom.ini ${PHP_CLI_CONF_DIR}/999-custom.ini && \
    ln -s ${PHP_MODS_DIR}/custom.ini ${PHP_FPM_CONF_DIR}/999-custom.ini && \
    # cleanup
    rm ${PHP_FPM_POOL_DIR}/www.conf && \
    apt-get clean -y && \
    apt-get autoclean -y && \
    apt-get autoremove -y && \
    rm -rf /var/lib/apt/lists/* /var/lib/log/* /tmp/* /var/tmp/* && \
    #
    # ln -sf /dev/stdout /var/log/fpm-access.log && \
    # ln -sf /dev/stderr /var/log/fpm-error.log; \
    #
    usermod -u 1000 www-data;

ADD conf.d/custom.ini ${PHP_MODS_DIR}/custom.ini
ADD fpm/php-fpm.conf ${PHP_FPM_DIR}/php-fpm.conf

VOLUME ["/var/www"]
WORKDIR /var/www

EXPOSE 9000

ENTRYPOINT ["php-fpm7.4"]
CMD ["-F","-R"]
