<?php

namespace Test;

use Braintree\Configuration;
use Braintree\Gateway;
use SilverStripe\Dev\SapphireTest;

class Setup extends SapphireTest {

    public static $valid_nonce_characters = 'bcdfghjkmnpqrstvwxyz23456789';

    public static $bt_environment = 'sandbox';
    public static $bt_merchantId = 't9nsccxkt5w699t6';
    public static $bt_publicKey = '8cvcgz9t7h2322gv';
    public static $bt_privateKey = '4ca8d78be3d35adf8c943198d1fa960f';

    public $gateway = null;

    public function __construct() {
        parent::__construct();
        self::setupBraintreeSettings();
    }

    public static function setupBraintreeSettings() {
        Configuration::reset();

        Configuration::environment('sandbox');
        Configuration::merchantId('t9nsccxkt5w699t6');
        Configuration::publicKey('8cvcgz9t7h2322gv');
        Configuration::privateKey('4ca8d78be3d35adf8c943198d1fa960f');
    }

    public function setUp() {
        if ($this->gateway == null) {
            $this->gateway = new Gateway([
                'environment' => self::$bt_environment,
                'merchantId' => self::$bt_merchantId,
                'publicKey' => self::$bt_publicKey,
                'privateKey' => self::$bt_privateKey
            ]);
        }

        return parent::setUp();
    }

    public function tearDown() {
        parent::tearDown();
    }
}