<?php

namespace Test\Unit;

require_once dirname(__DIR__) . '/Setup.php';

use SilverStripe\Security\Member;
use Test\Setup;

/**
 * Class BraintreePageControllerTest
 * @package Test\Unit
 */
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

    public function testStub() {
        $this->assertEquals(0, 0, '');
    }

    /**
     * Tear down for each test
     */
    public function tearDown() {
        parent::tearDown();
    }
}