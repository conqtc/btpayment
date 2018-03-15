<?php
namespace AlexT;

use SilverStripe\View\Requirements;

/**
 * Class BraintreeEditPageController
 *
 * Any SilverStripe page controller wishes to display a Braintree Dropin UI form to remove/add payment method must extend
 * this class
 * @package SilverStripe\Lessons
 */
class BraintreeEditPageController extends BraintreePageController {
    protected function init() {
        parent::init();
        // disable 'choose another way to pay' option in the Dropin UI
        Requirements::css('alext/silverstripe-btpayment: client/css/braintree-disable-add-new.css');
    }

}