# Usa imagem oficial do PHP com servidor embutido
FROM php:8.1-cli

# Copia os arquivos para dentro do container
COPY . /var/www/html

# Define o diretório padrão
WORKDIR /var/www/html

# Expõe a porta 8000
EXPOSE 8000

# Inicia o servidor embutido do PHP
CMD ["php", "-S", "0.0.0.0:8000", "-t", "."]
