FROM nginx:alpine
COPY .docker/nginx/default.conf /etc/nginx/conf.d
COPY ./app/ /var/www/html/

WORKDIR /var/www/html

