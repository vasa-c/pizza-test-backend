# Technologies and Implementation

Site implemented as single page application with server rendering for search engines.

## Frontend

* Vue.js
* Nuxt.js for SSR, that also contains
    * Node, Webpack, Vue-routing, Vuex
* Babel for transpilation JavaScript code to ES5 (for old browser support)

## Backend

Backend works only as API for frontend.
It receive ajax-requests and response JSON.

* PHP
* MySQL (SQLite for unit-tests)
* Laravel as PHP framework
* PHPUnit for tests

## Server

Developed in the following environment.
It can be changed.

* Nuxt server run and listening some local port (not accessible from the outside), for example `localhost:3000`.
* Laravels run with PHP-FPM
* Nginx listening requests to the host:
    * Requests inside `/api/` folder are proxy to the FPM socket
    * Other requests are proxied to the nuxt port
