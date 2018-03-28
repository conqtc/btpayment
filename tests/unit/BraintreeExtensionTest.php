<?php

namespace Test\Unit;

require_once dirname(__DIR__) . '/Setup.php';

use AlexT\BTPayment\BraintreeExtension;
use Braintree;
use SilverStripe\Dev\SapphireTest;
use SilverStripe\Security\Security;
use Test\Setup;

class BraintreeExtensionTest extends Setup {

    public static function setupBeforeClass() {
        parent::setUpBeforeClass();
        //
    }

    public function testSample() {
        $this->assertEquals(1, 1);
    }

    public static function tearDownAfterClass() {
        parent::tearDownAfterClass();
        //
    }
}