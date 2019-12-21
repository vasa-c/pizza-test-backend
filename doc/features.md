# Site Features

* Pizza. There are several types. Each type has the following parameters:
    * Pizza name
    * Photo
    * Description
    * Price (no discounts)
    * Available in infinite quantity
* Order
    * Selected pizza types and quantity for each
    * User-customer info: e-mail, address, contacts
    * Delivery within the city or outside
        * If outside the city delivery cost is 1 euro
        * If total price more than 100 euros delivery is free
    * Currency of the customer
    * Total price in customer currency
    * Status
        * `created` - order has just been placed
        * `delivery` - order is delivering
        * `success` - order delivered and money received
        * `fail` - not delivered for some reason
* Price and currency
    * Each pizza type has price in EUR
    * Order price is sum all selected pizzas and cost of delivery
    * User can change currency to USD and price will recalculated with fixed course (set in the configuration)
* Users
    * No separated registration form
    * Guest (not authorized user) can pick pizza to the shopping cart and send order
    * When the order is placed the customer automatically registered as user with specified e-mail
    * Random password will be generated and sent to the user inside the order mail
    * If authorized user make order then this order binds with him
    * If guest specified e-mail already exists then he must enter own password    
    * @todo email activation, change e-mail and password are not implemented
    * User parameters:
        * Email (unique)
        * Name
        * Address
        * Contacts
        * These data is save when ordering and are substituted to next order form
* Order processing (after customer created it, status=`created`)
    * Mail to the customer with order data and password if the user was registered
    * Mail to administration (admin mail specified in the system config)
    * Admin call to the customer and clarify details. As a result:
        * Order to delivery (status=`delivery`)
        * Order cancel (status=`fail`)
    * After delivery status finally change to `success` or `fail` 
* User cabinet
    * Authorized user can enter to own cabinet
    * It contains list of all user orders with statuses
* Admin
    * Access only for admins
    * When system install created one admin user with following parameters
        * `email` - admin mail from the config
        * `password` - "admin"
    * Admin sees list of orders and can change statuses
