# Layout and Pages

The site adopted for two resolutions.
`Mobile` (width <= 767) and `Desktop`.

## Desktop layout

Contains following parts of page:

* Header
    * Logo with link to the main page
    * Currency select
    * User block
        * Shopping cart (an icon with the count of pizzas), link to the cart page
        * For guests
            * Login link which opens login-popup (see below)
        * For authorized
            * User name - link to the cabinet
            * Logout link
            * Link to the admin area (for admins)
* Left menu
    * List of pizza types
    * If we are on a pizza page then item of this pizza are highlited
* Main section - content of the current page
* Footer

Page width is limited by 1280 px. 

## Mobile layout

Contains the following differences:

* No left menu, page content covers the entire width
* No user block in the header
* Icon which open mobile menu (instead user block)
* Mobile menu contains:
    * Links from user block: cart, login, cabinet, logout, admin
    * Items from desktop left menu (list of pizza)

## Login popup

Opens for guest by login links.
Form with `email` & `password` fields.

* If login is success close popup
* If fail show error message

## `/` - main page

Some text and list of pizza types with photos.

## `/pizzas/`

List of pizza types with photos.

## `/pizzas/slug/`

Page of the pizza type

* Pizza name
* Photo
* Description
* Price in the selected currency
* Button "add to cart" (if not added yet)
* If already added - change the count

### `/cart/`

If the shopping cart is empty page contains text about it and nothing more.

* List of selected pizzas
    * Change of the count
    * Delete from cart
* Total price in the selected currency
* Order form
    * email field for guest 
    * user name/email for authorized
    * address field
    * contacts field
    * select place inside/outside the city
* Button "send order"
    * disabled if not all required fields are filled
    * after click check email for guest and open login popup if email already exists
    * if all data correct create order and redirect user to the order page

### `/cabinet/`

* Available to all authorized users
* List of user orders with links to order page

### `/cabinet/{order-number}`

* Status and parameters the order
* Available to owner of the order

You can access the page in the following ways:

* Redirect after order sending
* Link in the order mail
* From order list in the cabinet

### `/admin/`

* Available only for admins
* List of all orders grouped by status

### `/admin/{order-number}`

* Available only for admins
* Order data
* Can change status:
    * `created` to `delivery` or `fail`
    * `delivery` to `success` or `fail`
    * `success` and `fail` are final statuses and cannot changed 
