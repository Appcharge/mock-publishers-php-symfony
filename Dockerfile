# Use an official PHP runtime as a parent image
FROM php:8.2-apache
# Set the working directory to /app
WORKDIR /app

# Install the required packages and extensions
RUN apt-get update

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Copy the application files to the container
COPY . /app

# Install GuzzleHttp library
RUN COMPOSER_ALLOW_SUPERUSER=1 composer require symfony/flex
RUN COMPOSER_ALLOW_SUPERUSER=1 composer require guzzlehttp/guzzle:^7.0
RUN curl -sS https://get.symfony.com/cli/installer | bash
RUN mv /root/.symfony5/bin/symfony /usr/local/bin/symfony
RUN chmod +x start.sh

# Expose port 80
EXPOSE 80

# Start the Apache server
CMD [ "/app/start.sh" ]