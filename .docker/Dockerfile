FROM php:7.4

RUN apt-get update -y
RUN apt-get install -y zip git
RUN rm -r /var/lib/apt/lists/*

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

CMD ["php", "-a"]
