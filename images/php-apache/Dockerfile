FROM php:7.4-apache

RUN apt-get update && docker-php-ext-install mysqli pdo pdo_mysql \
&& apt install -y libicu-dev libpng-dev \
&& docker-php-ext-configure intl \
&& docker-php-ext-install intl \
&& docker-php-ext-configure pcntl \
&& docker-php-ext-install pcntl \
&& apt-get install -y zip libzip-dev \
&& docker-php-ext-install zip \
&& apt-get install -y libmagickwand-dev --no-install-recommends && rm -rf /var/lib/apt/lists/* \
&& printf "\n" | pecl install imagick \
&& docker-php-ext-enable imagick \
&& a2enmod rewrite \
&& curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY --from=composer /usr/bin/composer /usr/bin/composer

EXPOSE 80
