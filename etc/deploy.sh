#!/usr/bin/env bash

cd "$(dirname "$0")/.." \
&& composer install --ignore-platform-reqs \
&& ./artisan migrate
