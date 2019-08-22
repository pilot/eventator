FROM phpmentors/symfony-app:php70

# default document root is /var/app/web folder 
COPY ./ /var/app

# example how to install app in the container
# RUN apt-get update && \
#    apt-get install -y curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN apt-get update && apt-get install -y \
    unzip \
    libssl-dev \
    libzip-dev \
    zlib1g-dev \
    libgs-dev \
    cron
RUN apt-get install -y libmagickwand-dev --no-install-recommends

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer --version

VOLUME ["/var/app"]

WORKDIR /var/app

EXPOSE 80