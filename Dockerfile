FROM php:apache-bullseye

# Install PDO & MySQL
RUN docker-php-ext-install pdo pdo_mysql

# Enable htaccess
RUN a2enmod rewrite

# Change document root
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Install composer
COPY --from=composer/composer:latest-bin /composer /usr/bin/composer

RUN apt update
# Install other tools
RUN apt install -y nano curl
RUN curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.7/install.sh | bash

RUN apt-get install -y nodejs
RUN apt-get install -y npm
