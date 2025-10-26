FROM php:8.2-fpm

# Instala dependências do sistema
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql mysqli mbstring xml

# Configurações do PHP
RUN echo "upload_max_filesize = 100M" >> /usr/local/etc/php/conf.d/uploads.ini \
    && echo "post_max_size = 100M" >> /usr/local/etc/php/conf.d/uploads.ini \
    && echo "memory_limit = 256M" >> /usr/local/etc/php/conf.d/memory.ini

# Define o diretório de trabalho
WORKDIR /var/www/html

# Instalar o OpenTelemetry PHP via Composer
# Etapa: instalar dependências do PHP
RUN apt-get update && apt-get install -y \
    git unzip autoconf build-essential wget libcurl4-openssl-dev pkg-config

# Baixar e compilar a extensão OpenTelemetry
RUN pecl install opentelemetry-beta \
    && docker-php-ext-enable opentelemetry

# Instalar o Composer e o pacote PHP do OpenTelemetry
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && echo '{ \
        "name": "php/app", \
        "description": "PHP App with OpenTelemetry", \
        "require-dev": { \
            "open-telemetry/opentelemetry-auto-psr3": "*" \
        } \
    }' > composer.json \
    && composer install --no-interaction --prefer-dist --no-progress


# Configuração do OpenTelemetry PHP
ENV OTEL_SERVICE_NAME="php-app"
ENV OTEL_EXPORTER_OTLP_ENDPOINT="http://otel-collector:4318"
ENV OTEL_TRACES_EXPORTER="otlp"
ENV OTEL_METRICS_EXPORTER="otlp"
ENV OTEL_LOGS_EXPORTER="otlp"
ENV OTEL_PHP_AUTOLOAD_ENABLED=true

# Script de saúde
HEALTHCHECK --interval=30s --timeout=3s --start-period=5s --retries=3 \
    CMD php -r "if (fsockopen('127.0.0.1', 9000)) exit(0); exit(1);"
