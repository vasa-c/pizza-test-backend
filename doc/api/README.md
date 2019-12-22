# API methods

For example if method is `layout` then request URL is `/api/layout`.
If not specified `(POST)` then it GET-method. 

## `layout`

Requested when frontend rendered on server side.
Returns base data for all pages.

* `pizza_types` - list of all pizza types for menu (array with items)
    * `name` - pizza name
    * `slug` - slug for pizza page URL
    * `photo` - link to photo preview
    * `prices` - list of prices, currency => price as integer cents
        * For example: "usd" => 123 ($ 1.23)
* `user` - data of authorized user (NULL for guest)
    * `email`
    * `name`
    * `is_admin` (bool)
    * `currency`, `address`, `contacts` - data from last order
* `currencies` - list of available currencies (first element is default currency)
    * key - "usd" for example
    * label - currency sign ("$")
* `csrf` - CSRF token for POST requests

## `login` (POST)

Input:

* `email`
* `password`

Output:

* `user` - data of user (such as in `layout` request, see above), NULL if login was failed

## `logout` (POST)

POST request without data.

## `pizza/{slug}`

Data of the specified pizza type.

* `pizza` -  such as an item in `layout` request (see above `pizza_types`)
    * `description` additional

## `checkout` (POST)

Order. Request:

* `pizza` - dictionary
    * pizza slug => count
* `currency`
* `email` - for guest
* `name`
* `address`
* `contacts`
* `outside` - delivery outside the city

*Note:* after successfully user registration he will automatically login 

Response:

* `order_number` - for success, redirect to `/cabinet/{order_number}`
* `user` - user data if was created (such as in `layout` request)
* `req_login` - TRUE if email already exists 

## `cabinet`

## `cabinet/{order-number}`

## `admin`

## `admin/{order-number}`
