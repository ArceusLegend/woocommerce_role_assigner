# What is this

The WooCommerce Role Assigner is a lightweight and efficient tool to automatically assign any selected role (default a Pro Customer role that is created automatically upon activating the plugin)
to a customer who has completed an order with WooCommerce. It will automatically fetch all current user roles and allow to choose which one you want to assign.

# How to install

Best and easiest solution is to simply install via the WP plugin interface. If you want to download manually, either download the ZIP file and decompress in your `wp-content/plugins` folder,
or clone it directly in that same directory with `git clone`. You will need to have [composer](https://getcomposer.org/download/) installed in order to download all the dependencies in the
`composer.lock` file. After that, simply go to the WP back end UI, and activate the plugin there.

# IMPORTANT NOTE
The actual functionality of assigning a role to a user who has completed an order is ***UNTESTED*** because WooPayments is being a little shithead on my local environment. I will update this as soon as I can.
Otherwise, the database related functionality works just fine... except for the part that for whatever reason the first time you try to save the role option, the database will create a new row in the table,
despite the fact that it clearly shouldn't. This seems to be an issue with MySQL as I was able to reproduce the query that the `$wpdb` object may use to update this row and found that for some reason it can't
find that specific row by id. I have no idea how to fix that, and it's 3am. Since this doesn't break anything, I will simply not look into this most likely.
