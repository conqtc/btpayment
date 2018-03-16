<?php
namespace AlexT\BTPayment;

use SilverStripe\ORM\DataObject;

class BraintreeTransaction extends DataObject {
    private $transaction;

    /**
     * BraintreeTransaction constructor.
     * @param null $transaction
     */
    public function __construct($transaction = null) {
        parent::__construct();
        $this->transaction = $transaction;
    }

    /*
    public function __get($key) {
        try {
            return $this->transaction->$key;
        } catch (Exception $e) {}

        return parent::__get($key);
    }
    */

    /**
     * @return mixed
     */
    public function getAmount() {
        return $this->transaction->amount;
    }

    /**
     * @return mixed
     */
    public function getStatus() {
        return $this->transaction->status;
    }

    /**
     * @return mixed
     */
    public function getType() {
        return $this->transaction->type;
    }

    /**
     * @return mixed
     */
    public function getCurrency() {
        return $this->transaction->currencyIsoCode;
    }

    /**
     * @return mixed
     */
    public function getDate() {
        return $this->transaction->createdAt->format('h:i A d/m/Y');
    }

    /**
     * @return mixed
     */
    public function getCustomer() {
        return $this->transaction->customer;
    }

}