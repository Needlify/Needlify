FROM php:8.1.9-apache

ENV TZ="Europe/Paris"

WORKDIR /var/www

RUN sh -c "$(curl --location https://taskfile.dev/install.sh)" -- -d -b  /usr/local/bin

# RUN docker-php-ext-install pdo_mysql && docker-php-ext-enable pdo_mysql
# RUN apt-get install -y libicu-dev && docker-php-ext-configure intl && docker-php-ext-install intl
# RUN apt-get install -y libfreetype6-dev libjpeg62-turbo-dev libpng-dev && docker-php-ext-configure gd --with-freetype --with-jpeg && docker-php-ext-install -j$(nproc) gd
# RUN docker-php-ext-install opcache

# RUN apt-get install -y libzip-dev zip && docker-php-ext-install zip
# RUN pecl install pcov && docker-php-ext-enable pcov

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions
RUN install-php-extensions pdo_mysql intl gd opcache zip dom pcov exif

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

RUN curl -fsSL https://deb.nodesource.com/setup_16.x | bash -
RUN apt install -yqq nano nodejs

COPY ./apache/vhosts.conf /etc/apache2/sites-available/000-default.conf

CMD apachectl -D FOREGROUND
