<?php

namespace Test\Functional;

require_once dirname(__DIR__) . '/SetupFunctional.php';

use AlexT\BTPayment\BraintreeExtension;
use AlexT\BTPayment\BraintreePageController;
use SilverStripe\Control\Controller;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\Form;
use SilverStripe\Forms\TextField;
use SilverStripe\Security\Member;
use SilverStripe\Security\Security;
use Test\SetupFunctional;

/**
 * Class BTPaymentFormTest
 * @package Test\Functional
 */
class BTEditPaymentFormTest extends SetupFunctional {

    protected static $extra_controllers = [
        TestEditController::class
    ];


    /**
     *
     */
    public function setUp() {
        return parent::setUp();
    }

    /**
     * Test if field values are match with what passed in
     */
    public function testFormFields() {
        $initialValue = 10;

        $controller = new BraintreePageController();
        $form = $controller->BTEditPaymentForm();

        // load mock data
        $requestData = array(
            'bted-payment_method_nonce' => self::$invalid_nonce_characters,
            'bted-payment_methods-length' => 1
        );
        $form->loadDataFrom($requestData);

        $fields = $form->Fields();
        $this->assertEquals(self::$invalid_nonce_characters, $fields->fieldByName('bted-payment_method_nonce')->Value());
        $this->assertEquals(1, $fields->fieldByName('bted-payment_methods-length')->Value());
    }

    /**
     * Test if the form created has a bt-dropin div for Braintree drop-in UI
     */
    public function testFormDropin() {
        $this->get('EditFormTest_Controller');

        //
        $response = $this->post(
            'EditFormTest_Controller/Form',
            array (
                'bted-payment_method_nonce' => self::$invalid_nonce_characters,
                'bted-payment_methods-length' => 1
            )
        );

        // response should contain 'bt-dropin' div for Braintree dropin UI
        $this->assertContains('<div id="bted-dropin" class="js-bted-dropin"></div>', $response->getBody());
    }

    /**
     *
     */
    public function tearDown() {
        parent::tearDown();
    }
}