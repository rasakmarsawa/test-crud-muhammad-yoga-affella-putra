FROM php:5.6-apache

RUN docker-php-ext-install mysql

RUN a2enmod rewrite
