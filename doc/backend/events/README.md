# Events & Notifications

## `CheckoutEvent`

Throws after a customer create new order.
Sends two notifications.

* `OrderForCustomerNotification`
    * Sends to the customer email
    * Contains info and link to the order
    * Contains email/password if the user was created
* `OrderForAdminNotification`
    * Sends to the admin email (from config)
    * Contains info about the order and link to the admin area
