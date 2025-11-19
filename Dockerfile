FROM hyperf/hyperf:8.1-alpine-v3.18-swoole

# Definir diretório de trabalho
WORKDIR /opt/www

# Instalar dependências do sistema e extensões necessárias
RUN apk add --no-cache \
    autoconf \
    g++ \
    make \
    openssl-dev \
    pcre-dev \
    php81-dev \
    php81-pear \
    pkgconfig

# Instalar extensão MongoDB via PECL
RUN pecl install mongodb \
    && echo "extension=mongodb.so" > /etc/php81/conf.d/50_mongodb.ini

# Copiar o código da aplicação
COPY . .

# Instalar dependências do Composer
RUN composer install --no-dev --prefer-dist --ignore-platform-req=ext-mongodb \
    && composer dump-autoload --optimize

# Expor porta
EXPOSE 9501

# Comando padrão
CMD ["php", "bin/hyperf.php", "start"]
