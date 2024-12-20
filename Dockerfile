FROM php:8.1-fpm

WORKDIR /var/www/html
RUN apt-get update && \
    apt-get install -y --no-install-recommends \
        git \
        unzip \
        curl

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
COPY . .
RUN composer install
EXPOSE 9000
CMD ["php-fpm"]
RUN useradd -ms /bin/bash sail
USER sail
