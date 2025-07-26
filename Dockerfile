FROM php:8.1-cli

WORKDIR /var/www/html

# Instala dependências
RUN apt-get update && apt-get install -y unzip git

# Instala Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copia os arquivos
COPY . .

# Instala dependências PHP (Dotenv)
RUN composer install

# Expõe porta e inicia o servidor
EXPOSE 8000
CMD ["php", "-S", "0.0.0.0:8000", "webhook.php"]
