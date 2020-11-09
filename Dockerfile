FROM mattrayner/lamp:latest-1804

RUN add-apt-repository ppa:ondrej/php \
  && apt-get -y update \
  && apt-get -y install php7.4-phalcon

WORKDIR /app
ADD composer.json ./
RUN composer install
ADD . ./

RUN chmod +x ./sakura-cli.sh