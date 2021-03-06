# `orders`

* `id`
* `number` - unique order number for frontend (hide real ID)
* `user_id` - customer, FK to [users](users.md)
* `user_created` - user was registered after this order
* `email`, `user_name`, `address`, `contacts` - fields from forms may differ from current customer data
* `currency` - currency of the order
* `outside` (boolean) - delivery outside the city
* `delivery_price` - delivery cost in the `currency`
* `total_price` - total price in the `currency` (including `delivery_price`)
* `status`
    * `created`
    * `delivery`
    * `success`
    * `fail`
* `created_at` - time when customer create order
* `finalized_at` - time of final status (`success` or `fail`)
