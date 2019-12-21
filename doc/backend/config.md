# Backend configuration

Copy file `.env.example` (in root directory) to `.env` and change following variables.

## `APP_URL`

Full URL of the site.
For example `https://pizza-test.com`.

* Though Laravel handle only requests to the `/api/` directory it URL to the whole site
* The site must placed in the domain root (not in subdirectory)

## `APP_ENV`

* `production` for production
* `local` for developer copy

## `DB_*`

Standard Laravel parameters of database connection.

## `MAIL_*`

Standard Laravel parameters of [mailing driver].

If there is no desire to customize you can set `MAIL_DRIVER=log` or `MAIL_DRIVER=array`.
See also `GENERATE_PASSWORD_AS_EMAIL` below.

## `ADMIN_EMAIL`

* Admin notifications (about new order for example) will be sent to this email
* When install system will be created one admin with this email (and `admin` as password)

## `GENERATE_PASSWORD_AS_EMAIL`

If you have not configured real mailing (and don't want to look in the log) you don't receive user random password after registration.
If set this variable to `true` then password will be created as user email.

For example, you entered `me@example.com` as email then will be created user with

* `email`="me@example.com"
* `password`="me@example.com"

## `USD_RATE`

Rate dollar to euro.
For example

* USD_RATE=0.9
* Pizza price is 12.99 euro
* Price in USD is 14.43

## `NGINX_*`

Options for automatically generate Nginx config file.

* `NGINX_LISTEN`: by default "80"
* `NGINX_NUXT_PROXY`: address of nuxt daemon (by default "http://localhost:3000")
* `NGINX_FPM`: PHP-FPM alias if not standard (by default "php-fpm")
