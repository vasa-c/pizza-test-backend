# `order_items`

Order pizza type list.

* `id`
* `order_id` - FK to [orders.id](orders.md)
* `pizza_type_id` - FK to [pizza_types.id](pizza_types.md)
* `count` - count of items
* `currency` - the order currency ("usd"|"eur")
* `total_price` - total amount of all items in the specified currency
* `item_price` - price of single item in euro (not in currency) in the time of the order
