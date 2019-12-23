# Backend Install

* Check [system requirements](../requirements.md)
* Clone repo: `cd {backend_root} && git clone https://github.com/vasa-c/pizza-test-backend.git`
* Prepare MySQL database
* [Configure](config.md)

## Scripts for install & deploy

There are two scripts

* `./etc/install.sh`
* `./etc/deploy.sh`

`install.sh` runs one time when install system.
`deploy.sh` runs each time then system update.
Deploy also is part of install.

**Note**: these scripts use `composer` as global command.

## Manually 

### `composer install`

It is first part of install and deploy.

### `key:generate`

`./artisan key:generate` set application key (in `.env`) to a random string.
This key needed for encrypt session data.

### chmod

If owner of repo files, user who run artisan and user of php-fpm daemon is different users you must verify the following: 

* artisan-user can write to the `storage`
* fpm-user can write to the `storage/framework/views`
* artisan and fpm both can write to `storage/logs/laravel.log`

### artisan migrate

Last part of install and deploy

## Web Server

Artisan command [install:nginx](artisan/install_nginx.md) creates nginx config in `storage/app/nginx.conf`.
This command runs from `install.sh`.
You can just use it (root is needed):

```
ln -s {backend-root}/storage/app/nginx.conf /etc/nginx/sites-enabled/pizza.conf
nignx -t && nginx -s reload
```

Or use your solution.

* All requests to the `/api` is backend requests and must handle by `{backend-root}/public/index.php`
* Other requests is frontend requests and must handle by nuxt daemon
