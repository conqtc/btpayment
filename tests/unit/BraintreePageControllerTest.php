<?php

namespace Test\Unit;

require_once dirname(__DIR__) . '/Setup.php';

use Test\Setup;

class BraintreePageControllerTest extends Setup {
    private $member;

    /**
     * Onetime setup for this test class
     */
    public static function setupBeforeClass() {
        parent::setUpBeforeClass();
        //
    }

    /**
     * Onetime tear down for this test class
     */
    public static function tearDownAfterClass() {
        parent::tearDownAfterClass();
        //
    }

    /**
     * Initialize new SS member
     */
    public function setUp() {
        parent::setUp();

        $this->member = new Member();
        $this->member->FirstName = 'Alex';
        $this->member->Surname = 'Truong';
    }
    

    /**
     * Tear down for each test
     */
    public function tearDown() {
        parent::tearDown();
    }
}