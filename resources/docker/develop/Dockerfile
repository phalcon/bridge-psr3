# syntax=docker/dockerfile:1
ARG PHP_VERSION=8.4

FROM php:${PHP_VERSION}-fpm AS base

# Installs the PHP extensions phalcon/phalcon needs plus xdebug for coverage.
# The phalcon framework itself is provided by the composer package, not the C extension.

ARG PHP_VERSION=8.4
ARG UID=1000
ARG GID=1000
ARG USER=phalcon
ARG GROUP=phalcon

RUN <<EOT
set -eux
# Add user and group
groupadd -g "${GID}" "${GROUP}"
useradd -l -m -u "${UID}" -g "${GID}" "${USER}"
usermod -s /bin/bash "${USER}"
install -d -m 0770 -o "${USER}" -g "${GROUP}" /srv
# Install needed packages
apt update -y
apt install -yq --no-install-recommends \
    apt-utils \
    git \
    libonig-dev \
    libzip-dev \
    locales \
    nano \
    sudo \
    unzip \
    wget \
    zip
sed -i -e 's/# en_US.UTF-8 UTF-8/en_US.UTF-8 UTF-8/' /etc/locale.gen
dpkg-reconfigure --frontend=noninteractive locales
update-locale LANG=en_US.UTF-8
# Allow git to operate on the bind-mounted repo when the container runs as root
git config --system --add safe.directory /srv
# Install xdebug (coverage)
pecl install xdebug
# Install extensions required by phalcon/phalcon
docker-php-ext-install \
    mbstring \
    zip
# Enable extensions
docker-php-ext-enable xdebug
# Copy ini file
cp -p /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini
# Set correct pid file location and permissions
install -d -m 0770 -o "${USER}" -g "${GROUP}" /run/php
# Cleanup
apt-get autoremove --purge -y
apt-get autoclean -y
apt-get clean -y
rm -rf /tmp/* /var/tmp/*
find /var/cache/apt/archives /var/lib/apt/lists -not -name lock -type f -delete
find /var/cache -type f -delete
find /var/log -type f -delete
EOT

# hadolint ignore=DL3022
COPY --from=composer/composer:2 --chown=${USER}:${GROUP} --chmod=0770 /usr/bin/composer /usr/bin/composer
COPY resources/docker/develop/.bashrc /home/${USER}/.bashrc
COPY resources/docker/develop/extra.ini /usr/local/etc/php/conf.d/

# User/Group globals
ENV LANG=en_US.UTF-8 \
    LANGUAGE=en_US.UTF-8 \
    LC_ALL=en_US.UTF-8

SHELL [ "/bin/bash", "--rcfile", "/home/${USER}/.bashrc", "-o", "pipefail", "-c" ]

WORKDIR /srv

#USER ${USER}
