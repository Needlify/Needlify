FROM php:8.1.9-apache

RUN sh -c "$(curl --location https://taskfile.dev/install.sh)" -- -d -b  /usr/local/bin

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN chmod +x /usr/local/bin/install-php-extensions && \
    install-php-extensions pdo pdo_mysql intl gd opcache zip dom pcov mbstring

RUN curl -sSk https://getcomposer.org/installer | php -- --disable-tls && \
   mv composer.phar /usr/local/bin/composer

RUN curl -fsSL https://deb.nodesource.com/setup_16.x | bash -

RUN apt install -yqq nano nodejs

RUN chmod -R 0777 /var/www/

COPY ./apache/vhosts.conf /etc/apache2/sites-available/000-default.conf

ENTRYPOINT [ "bash", "./docker.sh" ]

WORKDIR /var/www
