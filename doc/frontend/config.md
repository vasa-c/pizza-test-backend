# Frontend Configuration

Copy the file `env.json.example` (in the root dir) to `env.json` and edit it.

* `url` - the site address, `http://my-super-pizza.com` for example, it must be root of domain
* `port` - the nuxt daemon port. This port also must be specified in web server configuration for proxy all requests except `/api/*` (see [nginx config](../backend/artisan/install_nginx.md))
