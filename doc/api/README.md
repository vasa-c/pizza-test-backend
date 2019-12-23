# API methods

For example if method is `layout` then request URL is `/api/layout`.
If not specified `(POST)` then this is GET-method.

**NOTE**: all prices transfer to the frontend as integer (in cents), for example 3.99 will be 399 on frontend.

## `layout`

Requested when frontend rendered on server side.
Returns base data for all pages.

* `pizza_types` - list of all pizza types for menu (array with items)
    * `name` - pizza name
    * `slug` - slug for pizza page URL
    * `photo` - link to photo preview
    * `prices` - list of prices, currency => price
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
* `user` - current user data (such as in `layout` request), created or updated
* `req_login` - TRUE if email already exists 

## `cabinet`

Response:

* `orders` - list of the current user orders
    * `number`
    * `status`
    * `total_price`
    * `currency`
    * `created_at`
    * `finalized_at`

## `cabinet/{order-number}`

Response:

* `order` info of the specified order
    * `number`
    * `status`
    * `user_name`
    * `email`
    * `address`
    * `contacts`
    * `outside`
    * `currency`
    * `delivery_price`
    * `total_price`
    * `status`
    * `created_at`
    * `finalized_at`
    * `items` - list of pizza in the order
        * `slug`
        * `name`
        * `count`    

## `admin`

Response:

* `orders` - such as in `cabinet` method

## `admin/{order-number}`

Response:

* `order` - such as in `cabinet/{order-number}` method

## `admin/{order-number}/status` (POST)

Change status of the order.

Request:

* `status` - "delivery"|"success"|"fail"

Response:

* `order` - all data of the order after changes (see `admin/{order-number}).
