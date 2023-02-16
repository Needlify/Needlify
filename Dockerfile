FROM php:8.2.3-apache

ENV TZ="Europe/Paris"

WORKDIR /var/www

RUN sh -c "$(curl --location https://taskfile.dev/install.sh)" -- -d -b  /usr/local/bin

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions

RUN install-php-extensions pdo_mysql opcache intl gd opcache zip dom mbstring exif pcov

RUN curl -sSk https://getcomposer.org/installer | php -- --disable-tls && \
   mv composer.phar /usr/local/bin/composer

RUN curl -fsSL https://deb.nodesource.com/setup_16.x | bash -

RUN apt install -yqq nano nodejs

COPY ./apache/vhosts.conf /etc/apache2/sites-available/000-default.conf

CMD apachectl -D FOREGROUND
