<?php

namespace Test\Unit;

require_once dirname(__DIR__) . '/Setup.php';

use AlexT\BTPayment\BraintreeExtension;
use Braintree;
use SilverStripe\Dev\SapphireTest;
use SilverStripe\Security\Member;
use SilverStripe\Security\Security;
use Test\Setup;

class BraintreeExtensionTest extends Setup {
    private $member;

    /**
     *
     */
    public static function setupBeforeClass() {
        parent::setUpBeforeClass();
        //
    }

    /**
     *
     */
    public static function tearDownAfterClass() {
        parent::tearDownAfterClass();
        //
    }

    /**
     *
     */
    public function setUp() {
        parent::setUp();

        $this->member = new Member();
        $this->member->FirstName = 'Alex';
        $this->member->Surname = 'Truong';
    }

    /**
     *
     */
    public function testGatewayConfig() {
        $this->assertEquals(self::$bt_environment, $this->gateway->config->getEnvironment(), 'Environment should be: ' . self::$bt_environment);
        $this->assertEquals(self::$bt_merchantId, $this->gateway->config->getMerchantId(), 'Merchant ID should be: ' . self::$bt_merchantId);
        $this->assertEquals(self::$bt_publicKey, $this->gateway->config->getPublicKey(), 'Public key should be: ' . self::$bt_publicKey);
        $this->assertEquals(self::$bt_privateKey, $this->gateway->config->getPrivateKey(), 'Private key should be: ' . self::$bt_privateKey);
    }

    /**
     *
     */
    public function testCreateClient() {
        $cid = BraintreeExtension::BTCreateClient($this->gateway, $this->member);
        $cid2 = BraintreeExtension::BTCreateClient($this->gateway, $this->member);

        $this->assertNotEquals($cid, $cid2, 'Two different members with same attributes (firstname, surname) should have different client IDs');
    }

    /**
     * @throws \SilverStripe\ORM\ValidationException
     */
    public function testClientId() {
        // create new client id if neccessary
        $cid = BraintreeExtension::BTClientId($this->gateway, $this->member);
        $this->assertEquals($cid, $this->member->BTClientId, 'After new client is created, client id should be stored in member');

        // retrieve client id again, test if it is the same
        $cid2 = BraintreeExtension::BTClientId($this->gateway, $this->member);
        $this->assertEquals($cid, $cid2, 'BTClientId should return client id stored in existing member');
    }

    /**
     *
     */
    public function testClientToken() {
        $token = BraintreeExtension::BTClientToken($this->gateway, $this->member);
        $this->assertNotNull($token, 'Client token should not be null');
        $this->assertNotEquals('', $token, 'Client token should not be empty');
    }

    /**
     *
     */
    public function tearDown() {
        parent::tearDown();
    }
}