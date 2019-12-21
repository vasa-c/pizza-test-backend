# `install:nginx`

`./artisan install:nginx` builds standard `nginx.conf` for the site.

* `server_name` is taken from `APP_URL` (see [config](../config.md))
* All requests to `/api/` are proxies to PHP-FPM
* All other requests are proxies to Nuxt
* See NGINX_* variables in [config](../config.md) for customization
* Result will save to `storage/app/nginx.conf` 
