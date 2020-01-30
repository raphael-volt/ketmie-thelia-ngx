FROM php:7.2-apache

LABEL maintainer "Raphaël Volt <raphael@ketmie.com>"


RUN apt-get update && apt-get install -y \
    wget \
    nano \
    gettext \
    bzip2 \
    libbz2-dev \
    libcurl4-openssl-dev \
    libsqlite3-dev \
    libxml2-dev \
    libfreetype6-dev \
    libgd-dev \
    libmcrypt-dev \
    libmagickwand-dev libmagickcore-dev \
    libssl-dev \
    inotify-tools

# Imagemagick
RUN yes '' | pecl install -f imagick

# Install php extensions
RUN docker-php-ext-install bcmath
RUN docker-php-ext-install bz2
RUN docker-php-ext-install gettext
RUN docker-php-ext-install opcache
RUN docker-php-ext-install zip
RUN docker-php-ext-install pcntl
RUN docker-php-ext-install mysqli pdo pdo_mysql
RUN docker-php-ext-configure gd --with-freetype-dir=/usr --with-jpeg-dir=/usr
RUN docker-php-ext-install gd
RUN docker-php-ext-install gettext
RUN docker-php-ext-install intl
RUN docker-php-ext-enable imagick
RUN pecl install xdebug && docker-php-ext-enable xdebug


# PHPUNIT https://phar.phpunit.de/phpunit-8.5.2.phar
RUN wget -O phpunit-8.phar https://phar.phpunit.de/phpunit-8.5.2.phar
RUN chmod +x phpunit-8.phar
RUN mv phpunit-8.phar /usr/local/bin/phpunit
RUN phpunit --version

# Test watcher
COPY shared/phpunit.watch.sh /usr/local/bin/watch-phpunit
RUN chmod u+x,g+x /usr/local/bin/watch-phpunit
RUN echo "alias watch-phpunit='/usr/local/bin/watch-phpunit'" >> /root/.bashrc

# Apache Configuration
RUN a2enmod rewrite
RUN a2enmod headers

# SSL
RUN a2enmod ssl
RUN a2ensite default-ssl
RUN openssl req -subj '/CN=example.com/O=My Company Name LTD./C=US' -new -newkey rsa:2048 -days 365 -nodes -x509 -keyout /etc/ssl/private/ssl-cert-snakeoil.key -out /etc/ssl/certs/ssl-cert-snakeoil.pem

# Change file mod and owner
RUN usermod -u 1000 www-data
# RUN chown -R www-data:www-data /var/www/html/
# RUN chmod -R 0775 /var/www/html/
