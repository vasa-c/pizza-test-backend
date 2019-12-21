#!/usr/bin/env bash

cmd="./vendor/bin/phpunit -c ./phpunit.xml"

if [ "$#" -ne 0 ]; then
$cmd "$@"
else
$cmd
fi
