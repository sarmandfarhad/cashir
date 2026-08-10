# Base image for the Cashir Laravel POS Cloud Agent environment.
# Stable system toolchain lives here; repository dependencies are installed by
# the environment "install" step so they track the checked-out source.
FROM ubuntu:24.04

ENV DEBIAN_FRONTEND=noninteractive

# System tooling + PHP 8.4 (via the ondrej/php PPA, since Ubuntu 24.04 ships
# only PHP 8.3 and the locked Symfony 8.1 packages require PHP >= 8.4.1) +
# Node.js 22 (Vite 8 requires Node >= 20.19 / 22.12).
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        ca-certificates curl git gnupg unzip zip sqlite3 software-properties-common \
    && add-apt-repository -y ppa:ondrej/php \
    && curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get update \
    && apt-get install -y --no-install-recommends \
        nodejs \
        php8.4-cli \
        php8.4-sqlite3 \
        php8.4-mbstring \
        php8.4-xml \
        php8.4-curl \
        php8.4-gd \
        php8.4-bcmath \
        php8.4-intl \
        php8.4-zip \
    && update-alternatives --set php /usr/bin/php8.4 \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Composer (pinned major line) from the official Composer image.
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

# Ubuntu 24.04 already provides the "ubuntu" user (UID 1000); run as it so the
# checked-out repository and generated files are owned by a non-root user.
USER ubuntu
WORKDIR /home/ubuntu
