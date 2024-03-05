FROM php:8.2-fpm-alpine

RUN apk add --no-cache nginx wget

RUN mkdir -p /run/nginx

COPY docker/nginx.conf /etc/nginx/nginx.conf

RUN mkdir -p /app
COPY . /app
COPY ./src /app

RUN sh -c "wget http://getcomposer.org/composer.phar && chmod a+x composer.phar && mv composer.phar /usr/local/bin/composer"
RUN cd /app && \
    /usr/local/bin/composer install --optimize-autoloader --no-dev &&  \
    docker-php-ext-install pdo pdo_mysql

RUN chown -R www-data: /app

FROM node:18 as build

# WORKDIR /app

# COPY ./package*.json ./

RUN cd /usr/local/bin/ && npm install

# COPY . .

CMD sh /app/docker/startup.sh