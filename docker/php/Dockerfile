FROM php:7.3-apache

ARG CONTAINER_USER_ID
ARG CONTAINER_GROUP_ID
ARG CONTAINER_USER_NAME
ARG CONTAINER_GROUP_NAME

# create user and group
RUN addgroup --gid $CONTAINER_GROUP_ID $CONTAINER_GROUP_NAME \
    && adduser --disabled-password --gecos '' --uid $CONTAINER_USER_ID --gid $CONTAINER_GROUP_ID $CONTAINER_USER_NAME

# ドキュメントルートをデフォルトから修正
# ENV APACHE_DOCUMENT_ROOT /var/www/html/app/public
# RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
# RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf


COPY ./docker/php/etc/apache2/sites-available/000-default.conf /etc/apache2/sites-available/000-default.conf
COPY ./docker/php/tmp/ssl.sh /tmp/ssl.sh
RUN /bin/bash -c "source /tmp/ssl.sh"

COPY --from=composer:2.0 /usr/bin/composer /usr/bin/composer

# a2enmod rewrite は mod_rewriteを有効化(Laravelでは必須)
RUN apt-get update \
  && apt-get install -y libzip-dev zlib1g-dev libpq-dev mariadb-client unzip\
  && docker-php-ext-install zip pdo_mysql mysqli \
  && docker-php-ext-enable mysqli \
  && a2enmod rewrite ssl

# Nodeの準備
RUN apt-get install -y nodejs \
  npm

RUN npm install -g n
RUN n 16.14.0

# Vim
RUN apt-get install -y vim

# wget
RUN apt-get install -y wget

# mkcert
RUN wget https://github.com/FiloSottile/mkcert/releases/download/v1.4.3/mkcert-v1.4.3-linux-amd64
RUN mv mkcert-v1.4.3-linux-amd64 /usr/local/bin/mkcert
RUN chmod 755 /usr/local/bin/mkcert
