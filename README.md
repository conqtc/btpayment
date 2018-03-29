silverstripe-btpayment
======================
A SilverStripe module to integrate Braintree payment forms in Dropin UI.

Currently there're two forms:

* Make a payment form:

<img src="../../../_screenshots/blob/master/btpayment/make_payment.png" width=400 />

* Add/remove payment methods in the vault:

<img src="../../../_screenshots/blob/master/btpayment/manage_methods.png" width=400 />

* Display previous transactions simple list

<img src="../../../_screenshots/blob/master/btpayment/transactions.png" width=400 />

Support SilverStripe 4.

## Installation

Use `composer` to install/update:
```
composer require alext/silverstripe-btpayment
```

## Braintree settings

After installing and rebuilding (`\dev\build?flush`) go to site admin - Settings and input Braintree settings, see screenshot below:

<img src="../../../_screenshots/blob/master/btpayment/settings.png" width=400 />

## SilverStripe member and Braintree customer

This module extends SilverStripe member's data to create a Braintree customer for each member and store its customer id in database.

Braintree customer will be created on the fly at the first time using the forms if there's no customer id found. 

## Usage

* To use the make payment form, use `$BTPaymentForm` in your template of the page.

Example:
```
[SamplePayment.ss]
<!-- BEGIN MAIN CONTENT -->
    $BTPaymentForm
<!-- END MAIN CONTENT -->
```

The page controller must extend `BraintreePageController`
```
use AlexT\BTPayment\BraintreePageController;

class SamplePaymentPageController extends BraintreePageController {
}
```

* To use the payment methods management form, use `$BTEditPaymentForm($amount)` in your template, if $amount is not specified 0 (zero) will be put in place.

Example (in case we process the payment in the separate page, set the total amount on the fly):
```
[SamplePaymentManagement.ss]
<!-- BEGIN MAIN CONTENT -->
    $BTEditPaymentForm
<!-- END MAIN CONTENT -->
```

Example if we want to change the total amount on the fly:
```
[PaymentPage.ss]
<h3>Select a property to purchase:</h3>
<p><p>
<select id="js-select-property">
    <option value="0">----- Select property -----</option>
    <% loop $FeaturedProperties %>
        <option value="{$PricePerNight}">{$Title}</option>
    <% end_loop %>
</select>
<hr>
$BTPaymentForm
-----
[scripts.js]
$('#js-select-property').on('change', function (e) {
    $('.js-bt-amount').val($('#js-select-property').val());
});
```

The page controller must extend `BraintreeEditPageController`
```
use AlexT\BTPayment\BraintreeEditPageController;

class SamplePaymentManagementPageController extends BraintreeEditPageController {
}
```

* To use the previous transactions form, use `BTPreviousTransactionsForm` in your template.

Example:
```
[SamplePaymentManagement.ss]
<!-- BEGIN MAIN CONTENT -->
    $BTPreviousTransactionsForm
<!-- END MAIN CONTENT -->
```

The page controller must extend `BraintreePageController`.
