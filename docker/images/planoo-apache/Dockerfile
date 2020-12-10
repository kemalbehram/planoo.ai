###################
# The final image
###################

FROM php:7.4-apache

#mandatory tools
RUN apt-get update && apt-get install -y --no-install-recommends\
		locales \
		apt-utils \
		git \
		unzip \
		libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
		libicu-dev \
		libfontconfig1 \
		libfontconfig1-dev \
		libzip-dev \
		libbz2-dev \
		gnupg

#optionnal tools
RUN apt-get install -y --no-install-recommends \
		vim \
		nano

#php modules
RUN docker-php-ext-install gd \
	&& docker-php-ext-install intl \
	&& docker-php-ext-install bz2 \
	&& docker-php-ext-install pdo_mysql \
	&& docker-php-ext-install calendar \
	&& docker-php-ext-install zip

# Install Xdebug
RUN pecl install -f xdebug-2.9.8
RUN echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" > /usr/local/etc/php/conf.d/xdebug.ini;

#apache module
RUN a2enmod rewrite \
	&& a2enmod proxy \
	&& a2enmod proxy_fcgi
	
# PhantomJS requires  an OpenSSL config even if it's an empty one,
# else it'll complain about "libssl_conf.so: cannot open shared object file"
ENV OPENSSL_CONF=/etc/ssl

# Installation composer
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer\
	&& composer --version

# Installation  yarn
RUN curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | apt-key add -
RUN echo "deb https://dl.yarnpkg.com/debian/ stable main" | tee /etc/apt/sources.list.d/yarn.list

RUN apt-get update && apt-get install -y yarn

RUN echo "ServerName planoo" >> /etc/apache2/apache2.conf

#Installation phatomJS
RUN apt-get -y install build-essential chrpath libssl-dev libxft-dev wget \
	&& apt-get -y install libfreetype6 libfreetype6-dev \
	&& apt-get -y install libfontconfig1 libfontconfig1-dev

RUN wget https://bitbucket.org/ariya/phantomjs/downloads/phantomjs-2.1.1-linux-x86_64.tar.bz2
RUN tar xvjf phantomjs-2.1.1-linux-x86_64.tar.bz2
RUN mv phantomjs-2.1.1-linux-x86_64 /usr/local/share
RUN ln -sf /usr/local/share/phantomjs-2.1.1-linux-x86_64/bin/phantomjs /usr/local/bin
RUN phantomjs --version

# les locales, toujours utiles
RUN echo "en_US.UTF-8 UTF-8" > /etc/locale.gen && \
    echo "fr_FR.UTF-8 UTF-8" >> /etc/locale.gen && \
    locale-gen
	
# On copie le php.ini du repertoire actuel dans le contenaire
#/usr/local/etc/php/conf.d/php.ini
COPY ./conf/dev/php/php.ini /usr/local/etc/php/conf.d/php.ini
COPY ./conf/dev/apache/000-default.conf /etc/apache2/sites-enabled/000-default.conf
#COPY ./conf/dev/apache/planoo.conf /etc/apache2/conf-enabled/planoo.conf


#RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/conf.d/php.ini"
	
WORKDIR /var/www/html/bo

#RUN composer install --optimize-autoloader

# User 1000 is current system user, we associate it to www-data user
RUN usermod -u 1000 www-data
RUN groupmod -g 1000 www-data

RUN chown www-data:www-data /var/www

EXPOSE 80 443