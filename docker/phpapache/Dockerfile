FROM php:7.3-apache-stretch

RUN apt-get update && \  
    apt-get install -y apt-utils

ENV DEBIAN_FRONTEND noninteractive

# update des sources , installation utilitaire et git
RUN apt-get update && \
    apt-get upgrade -y && \
    apt-get install -y apt-utils git zip unzip nano

#######################################
########## Configuration PHP ##########
#######################################
ADD https://raw.githubusercontent.com/mlocati/docker-php-extension-installer/master/install-php-extensions /usr/local/bin/
RUN chmod uga+x /usr/local/bin/install-php-extensions && sync

# intl      requis par Symfony
# curl      requis par Guzzle
# zip       requis par composer
# pdo_mysql requis pour le projet
RUN install-php-extensions intl curl opcache zip pdo_mysql gd

# install composer
RUN curl --silent --show-error https://getcomposer.org/installer | php  && \
    mv composer.phar /usr/local/bin/composer

# création du fichier de configuration php ini, exposant des variables d'environnement
# l'absence de timezone défini provoque une erreur
ENV PHP_DATE_TIMEZONE Europe/Paris
# memoire max allouée
ENV PHP_MEMORY_LIMIT 512M
# max upload file size
ENV PHP_UPLOAD_MAX_FILESIZE 6M
# Sécurisation des cookies si HTTPS
ENV PHP_COOKIE_SECURE 0
# optimisation du cache opcache
ENV PHP_OPCACHE_VALIDATE_TIMESTAMP 1
# optimisation pour windows
ENV PHP_REALPATH_CACHE_SIZE 4096K
RUN echo "date.timezone = ${PHP_DATE_TIMEZONE}"                              > /usr/local/etc/php/php.ini && \
    echo "short_open_tag = off"                                             >> /usr/local/etc/php/php.ini && \
    echo "expose_php = Off"                                                 >> /usr/local/etc/php/php.ini && \
    echo 'session.cookie_secure=${PHP_COOKIE_SECURE}'                       >> /usr/local/etc/php/php.ini && \
    echo 'memory_limit=${PHP_MEMORY_LIMIT}'                                 >> /usr/local/etc/php/php.ini && \
    echo 'upload_max_filesize=${PHP_UPLOAD_MAX_FILESIZE}'                   >> /usr/local/etc/php/php.ini && \
    echo 'opcache.validate_timestamps=${PHP_OPCACHE_VALIDATE_TIMESTAMP}'    >> /usr/local/etc/php/php.ini && \
    echo 'realpath_cache_size=${PHP_REALPATH_CACHE_SIZE}'                   >> /usr/local/etc/php/php.ini

##########################################
########## Configuration APACHE ##########
##########################################
RUN a2enmod headers proxy rewrite ssl

# exposer la variable d'environnement permettant d'aller directement au répertoire public
# Sécurisation, ne pas exposer la version du server apache
ENV APACHE_DOCUMENT_ROOT /var/www/html
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf  && \
    sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf  && \
    sed -i -r 's/.*ServerTokens.*/ServerTokens Prod/' /etc/apache2/conf-available/security.conf  && \
    sed -i -r 's/.*ServerSignature.*/ServerSignature Off/' /etc/apache2/conf-available/security.conf

## expose le format de log apache access pour graylog
#COPY cogifor.conf /etc/apache2/conf-available/cogifor.conf
#RUN a2enconf cogifor

##########################################
########## Configuration NODE JS ##########
##########################################
RUN curl -sL https://deb.nodesource.com/setup_10.x | bash -   && \
    apt-get update  && \
    apt-get install -y nodejs  && \
    npm install -g yarn
