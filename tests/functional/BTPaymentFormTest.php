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
class BTPaymentFormTest extends SetupFunctional {

    protected static $extra_controllers = [
        TestController::class
    ];


    /**
     *
     */
    public function setUp() {
        return parent::setUp();
    }

    /**
     *
     */
    public function testGateway() {
        $this->assertNotNull($this->gateway, 'Gateway should not be null');
    }

    /**
     * Test if field values are match with what passed in
     */
    public function testFormFields() {
        $initialValue = 10;

        $controller = new BraintreePageController();
        $form = $controller->BTPaymentForm($initialValue);

        // load mock data
        $requestData = array(
            'bt-payment_method_nonce' => self::$invalid_nonce_characters,
        );
        $form->loadDataFrom($requestData);

        $fields = $form->Fields();
        $this->assertEquals($initialValue, $fields->fieldByName('bt-amount')->Value());
        $this->assertEquals(self::$invalid_nonce_characters, $fields->fieldByName('bt-payment_method_nonce')->Value());
    }

    /**
     * Test if the form created has a bt-dropin div for Braintree drop-in UI
     */
    public function testFormDropin() {
        $this->get('FormTest_Controller');

        //
        $defaultValue = 10;
        $response = $this->post(
            'FormTest_Controller/Form',
            array (
                'bt-amount' => $defaultValue,
                'bt-payment_method_nonce' => self::$invalid_nonce_characters,
            )
        );

        // response should contain 'bt-dropin' div for Braintree dropin UI
        $this->assertContains('div id="bt-dropin" class="js-bt-dropin"></div>', $response->getBody());
    }

    /**
     * Test with fake valid clien payment nonce
     */
    public function testFakeValidNonce() {
        $member = Member::get()->first();
        $this->logInAs($member);

        $this->get('FormTest_Controller');

        //
        $defaultValue = 1000;
        $response = $this->post(
            'FormTest_Controller/Form',
            array (
                'bt-amount' => $defaultValue,
                'bt-payment_method_nonce' => self::$fake_valid_nonce,
            )
        );

        // response should contain 'Unknown or expired paymentMethodNonce'
        $this->assertContains('A payment of '. $defaultValue .'$ has been made!', $response->getBody());
    }

    /**
     * Test massive amount enter in the form
     */
    public function testMassiveAmount() {
        $member = Member::get()->first();
        $this->logInAs($member);

        $this->get('FormTest_Controller');

        //
        $defaultValue = 1000000000;
        $response = $this->post(
            'FormTest_Controller/Form',
            array (
                'bt-amount' => $defaultValue,
                'bt-payment_method_nonce' => self::$fake_valid_nonce,
            )
        );

        // response should contain 'Unknown or expired paymentMethodNonce'
        $this->assertContains('Error: 81528: Amount is too large', $response->getBody());
    }

    /**
     * Test if amount is zero
     */
    public function testZeroAmount() {
        $member = Member::get()->first();
        $this->logInAs($member);

        $this->get('FormTest_Controller');

        //
        $defaultValue = 0;
        $response = $this->post(
            'FormTest_Controller/Form',
            array (
                'bt-amount' => $defaultValue,
                'bt-payment_method_nonce' => self::$fake_valid_nonce,
            )
        );

        // response should contain 'Unknown or expired paymentMethodNonce'
        $this->assertContains('Error: 81531: Amount must be greater than zero', $response->getBody());
    }

    /**
     * Test if amount is negative
     */
    public function testNegativeAmount() {
        $member = Member::get()->first();
        $this->logInAs($member);

        $this->get('FormTest_Controller');

        //
        $defaultValue = -1;
        $response = $this->post(
            'FormTest_Controller/Form',
            array (
                'bt-amount' => $defaultValue,
                'bt-payment_method_nonce' => self::$fake_valid_nonce,
            )
        );

        // response should contain 'Unknown or expired paymentMethodNonce'
        $this->assertContains('Error: 81501: Amount cannot be negative', $response->getBody());
    }

    /**
     * Test if amount is not number format
     */
    public function testNonNumberAmount() {
        $member = Member::get()->first();
        $this->logInAs($member);

        $this->get('FormTest_Controller');

        //
        $defaultValue = 'number';
        $response = $this->post(
            'FormTest_Controller/Form',
            array (
                'bt-amount' => $defaultValue,
                'bt-payment_method_nonce' => self::$fake_valid_nonce,
            )
        );

        // response should contain 'Unknown or expired paymentMethodNonce'
        $this->assertContains('Error: 81503: Amount is an invalid format', $response->getBody());
    }

    /**
     * Test invalid nonce
     */
    public function testInvalidNonce() {
        $member = Member::get()->first();
        $this->logInAs($member);

        $this->get('FormTest_Controller');

        //
        $defaultValue = 10;
        $response = $this->post(
            'FormTest_Controller/Form',
            array (
                'bt-amount' => $defaultValue,
                'bt-payment_method_nonce' => self::$invalid_nonce_characters,
            )
        );

        // response should contain 'Unknown or expired paymentMethodNonce'
        $this->assertContains('Error: 91565: Unknown or expired paymentMethodNonce', $response->getBody());
    }

    /**
     * Test empty nonce
     */
    public function testEmptyNonce() {
        $member = Member::get()->first();
        $this->logInAs($member);

        $this->get('FormTest_Controller');

        //
        $defaultValue = 10;
        $response = $this->post(
            'FormTest_Controller/Form',
            array (
                'bt-amount' => $defaultValue,
                'bt-payment_method_nonce' => '',
            )
        );

        // response should contain 'Unknown or expired paymentMethodNonce'
        $this->assertContains('Error: 91508: Cannot determine payment method', $response->getBody());
    }

    /**
     *
     */
    public function tearDown() {
        parent::tearDown();
    }
}