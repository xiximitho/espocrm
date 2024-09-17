# Use a imagem oficial do PHP com Apache
FROM php:8.2-apache

# Instalar dependências do sistema necessárias para o EspoCRM
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libicu-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    libonig-dev \
    libxml2-dev \
    cron \
    npm \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip intl

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configurações adicionais de PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd

# Configurações do Apache
RUN a2enmod rewrite

# Configurações de permissões
RUN chown -R www-data:www-data /var/www && chmod -R 755 /var/www
# Definir o diretório de trabalho
WORKDIR /var/www/html

# Copiar os arquivos do projeto para o container
COPY . /var/www/html

# Instalar dependências do Composer (se houver)
RUN composer install
RUN npm install
RUN npm run build

RUN cd /var/www/html && find data -type d -exec chmod 775 {} + && chown -R 33:33 .;
# Configurar o cron para rodar o EspoCRM
#RUN echo "* * * * * cd /var/www/html; /usr/local/bin/php -f cron.php > /dev/null 2>&1" >> /etc/crontab

# Expor a porta 80 para o Apache
EXPOSE 80

# Definir a execução do Apache como processo principal
CMD ["apache2-foreground"]

