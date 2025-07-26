FROM php:8.1-cli
WORKDIR /var/www/html
COPY . .
RUN apt-get update && apt-get install -y unzip
EXPOSE 8000
CMD ["php", "-S", "0.0.0.0:8000", "webhook.php"]