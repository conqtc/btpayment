<?php

namespace Test;

use Braintree\Configuration;
use Braintree\Gateway;
use SilverStripe\Dev\SapphireTest;

/**
 * Class Setup
 * @package Test
 */
class Setup extends SapphireTest {
    public static $bt_environment = 'sandbox';
    public static $bt_merchantId = 't9nsccxkt5w699t6';
    public static $bt_publicKey = '8cvcgz9t7h2322gv';
    public static $bt_privateKey = '4ca8d78be3d35adf8c943198d1fa960f';

    public $gateway = null;

    /**
     * Setup constructor.
     */
    public function __construct() {
        parent::__construct();
        self::setupBraintreeSettings();
    }

    /**
     * Setup Braintree environment settings
     */
    public static function setupBraintreeSettings() {
        Configuration::reset();

        Configuration::environment(self::$bt_environment);
        Configuration::merchantId(self::$bt_merchantId);
        Configuration::publicKey(self::$bt_publicKey);
        Configuration::privateKey(self::$bt_privateKey);
    }

    /**
     * Initialize a new gateway if not yet created
     */
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

    /**
     *
     */
    public function tearDown() {
        parent::tearDown();
    }
}