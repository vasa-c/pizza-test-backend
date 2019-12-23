#!/usr/bin/env bash

cd "$(dirname "$0")/.." \
&& ./artisan key:generate \
&& composer install --ignore-platform-reqs \
&& chmod 777 storage/framework/views \
&& touch storage/logs/laravel.log \
&& chmod 666 storage/logs/laravel.log \
&& ./artisan install:nginx \
&& ./artisan migrate
