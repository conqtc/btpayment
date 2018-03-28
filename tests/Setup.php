<?php

namespace Test;

use Braintree\Configuration;
use SilverStripe\Dev\SapphireTest;

class Setup extends SapphireTest {

    public static $valid_nonce_characters = 'bcdfghjkmnpqrstvwxyz23456789';

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

}