<?php

namespace Test\Unit;

require_once dirname(__DIR__) . '/Setup.php';

use AlexT\BTPayment\BraintreeTransaction;
use SilverStripe\Dev\SapphireTest;
use Test\Setup;

class BraintreeTransactionTest extends Setup {
    /**
     * Test passing in null to create new BraintreeTransaction
     */
    public function testNullTransaction() {
        $transaction = new BraintreeTransaction(null);

        $this->assertEquals(-1, $transaction->getAmount());

        $this->assertEquals('', $transaction->getStatus());

        $this->assertEquals('', $transaction->getType());

        $this->assertEquals('', $transaction->getCurrency());

        $this->assertEquals('', $transaction->getDate());

        $this->assertEquals('', $transaction->getCustomer());
    }
}