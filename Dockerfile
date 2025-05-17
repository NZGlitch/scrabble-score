FROM php:8.2-apache

RUN a2enmod rewrite

COPY . /var/www/

RUN chown -R www-data:www-data /var/www \
	&& chmod -R 0755 /var/www

EXPOSE 80
