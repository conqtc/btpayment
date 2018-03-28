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

        $this->assertEquals(-1, $transaction->getAmount(), 'Amount should be -1 when transaction is null');
        $this->assertEquals('', $transaction->getStatus(), 'Status should be empty when transaction is null');
        $this->assertEquals('', $transaction->getType(), 'Type should be empty when transaction is null');
        $this->assertEquals('', $transaction->getCurrency(), 'Currency should be empty when transaction is null');
        $this->assertEquals('', $transaction->getDate(), 'Date should be empty when transaction is null');
        $this->assertEquals('', $transaction->getCustomer(), 'Customer should be empty when transaction is null');
    }
}