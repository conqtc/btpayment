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
        if ($this->transaction != null) {
            return $this->transaction->amount;
        } else {
            return -1;
        }
    }

    /**
     * @return mixed
     */
    public function getStatus() {
        if ($this->transaction != null) {
            return $this->transaction->status;
        } else {
            return '';
        }
    }

    /**
     * @return mixed
     */
    public function getType() {
        if ($this->transaction != null) {
            return $this->transaction->type;
        } else {
            return '';
        }
    }

    /**
     * @return mixed
     */
    public function getCurrency() {
        if ($this->transaction != null) {
            return $this->transaction->currencyIsoCode;
        } else {
            return '';
        }
    }

    /**
     * @return mixed
     */
    public function getDate() {
        if ($this->transaction != null) {
            return $this->transaction->createdAt->format('h:i A d/m/Y');
        } else {
            return '';
        }
    }

    /**
     * @return mixed
     */
    public function getCustomer() {
        if ($this->transaction != null) {
            return $this->transaction->customer;
        } else {
            return '';
        }
    }

}