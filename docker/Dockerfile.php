FROM php:8.2-fpm-buster

ENV USER=docker-user

RUN apt update && apt install -y \
  curl \
  wget \
  git \
  libfreetype6-dev \
  libjpeg62-turbo-dev \
  libpng-dev \
  libonig-dev \
  libzip-dev \
  && docker-php-ext-install -j$(nproc) iconv mbstring mysqli pdo_mysql zip \
  && docker-php-ext-configure gd --with-freetype --with-jpeg \
  && docker-php-ext-install -j$(nproc) gd \
  && docker-php-ext-configure zip \
  && docker-php-ext-install zip \
  && docker-php-ext-install mysqli \
  && pecl install xdebug-3.2.0 \
  && docker-php-ext-enable xdebug \
  && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

#RUN useradd $USER && groupadd docker && usermod -aG docker $USER && chmod g+rwx /app && chgrp docker /app && newgrp - docker
#USER $USER

WORKDIR /app

#RUN if [ -e composer.json ]; then \
#  composer install \
#  --optimize-autoloader \
#  --no-interaction \
#  --no-progress \
#  --ignore-platform-reqs; \
#  else \
#  composer init --name=promastersss/php-project --author=ProMastersss; \
#  fi

COPY docker/conf/php.ini "$PHP_INI_DIR/php.ini"
COPY docker/conf/docker-php-ext-xdebug.ini "$PHP_INI_DIR/conf.d/docker-php-ext-xdebug.ini"

CMD ["php-fpm"]
