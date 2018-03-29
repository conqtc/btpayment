<?php

namespace Test\Functional;

require_once dirname(__DIR__) . '/SetupFunctional.php';

use Test\SetupFunctional;

/**
 * Class BTPaymentFormTest
 * @package Test\Functional
 */
class BTPaymentFormTest extends SetupFunctional {

    /**
     *
     */
    public function setUp() {
        return parent::setUp();
    }

    /**
     *
     */
    public function testStub() {
        $this->assertNotNull($this->gateway, 'Gateway should not be null');
    }

    public function testSubmit() {

    }

    /**
     *
     */
    public function tearDown() {
        parent::tearDown();
    }
}