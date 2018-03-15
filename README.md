silverstripe-btpayment
======================
A SilverStripe module to integrate Braintree payment forms in Dropin UI.

Currently there're two forms:
* Make a payment form:
![Make Payment](_screenshots/make_payment.jpg?raw=true "Make payment")
* Add/remove payment methods in the vault:
![Manage Payment Methods](_screenshots/manage_methods.jpg?raw=true "Manage payment methods")

Support SilverStripe 4.

## Installation

Use `composer` to install/update:
```
composer require alext/silverstripe-btpayment
```

## Braintree settings

After installing and rebuilding (`\dev\build?flush`) go to site admin - Settings and input Braintree settings, see screenshot below:
![Setting](_screenshots/settings.jpg?raw=true "Braintree settings")

## SilverStripe member and Braintree customer

This module extends SilverStripe member's data to create a Braintree customer for each member and store its customer id in database.

Braintree customer will be created on the fly at the first time using the forms if there's no customer id found. 

## Usage

To use the make payment form, use `$BTPaymentForm` in your template.

Example:
```
[SamplePayment.ss]
<!-- BEGIN MAIN CONTENT -->
    $BTPaymentForm
<!-- END MAIN CONTENT -->
```

To use the payment methods management form, use `$BTEditPaymentForm` in your template.

Example:
```
[SamplePaymentManagement.ss]
<!-- BEGIN MAIN CONTENT -->
    $BTEditPaymentForm
<!-- END MAIN CONTENT -->
```