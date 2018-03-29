<?php
namespace AlexT\BTPayment;

use Braintree_TransactionSearch;
use PageController;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\Form;
use SilverStripe\Forms\FormAction;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig;
use SilverStripe\Forms\GridField\GridFieldDataColumns;
use SilverStripe\Forms\GridField\GridFieldPaginator;
use SilverStripe\Forms\GridField\GridFieldSortableHeader;
use SilverStripe\Forms\GridField\GridFieldToolbarHeader;
use SilverStripe\Forms\HiddenField;
use SilverStripe\Forms\LiteralField;
use SilverStripe\Forms\RequiredFields;
use SilverStripe\Forms\TextField;
use SilverStripe\ORM\ArrayList;
use SilverStripe\Security\Security;
use SilverStripe\View\Requirements;

/**
 * Class BraintreePageController
 *
 * Custom PageController to include css and script files for Braintree Payment Gateway
 * @package SilverStripe\Lessons
 */
class BraintreePageController extends PageController {
    /**
     * {@inheritdoc}
     */
    private static $allowed_actions = [
        'BTPaymentForm',
        'BTEditPaymentForm',
        'BTPreviousTransactions'
    ];

    /**
     * Initialize the page
     *
     * Include necessary css and script files for Braintree Payment Gateway
     */
    protected function init() {
        parent::init();
        Requirements::javascript("https://js.braintreegateway.com/web/dropin/1.9.4/js/dropin.min.js");
        Requirements::javascript('alext/silverstripe-btpayment: client/js/btscripts.js');
    }

    /**
     * Generate a SilverStripe form to host the Braintree Dropin UI
     *
     * @return Braintree payment form using Dropin UI
     */
    public function BTPaymentForm($defaultValue = 0) {
        $gateway = BraintreeExtension::BTGateway();
        $member = Security::getCurrentUser();

        $form = Form::create(
            $this,
            __FUNCTION__,
            // amount input, <div> block to inject BT dropin UI, hidden field to store payment method nonce
            FieldList::create(
                TextField::create('bt-amount','TOTAL AMOUNT ($)')
                ->addExtraClass('js-bt-amount')
                ->setReadonly(true)
                ->setValue($defaultValue),
                LiteralField::create('', '<p><div id="bt-dropin" class="js-bt-dropin"></div>'),
                HiddenField::create('bt-payment_method_nonce', '')
                ->addExtraClass('js-bt-nonce')
            ),
            // Submit button
            FieldList::create(
                FormAction::create('makePayment','Make Payment')
                    ->setUseButtonTag(true)
                    ->addExtraClass('btn btn-default-color btn-sm js-bt-button-make-payment')
            ),
            //
            RequiredFields::create('Amount')
        )
        ->addExtraClass('js-bt-payment-form')
        // add client token as data attribute
        ->setAttribute('data-client-token', BraintreeExtension::BTClientToken($gateway, $member));

        // retrieve data from saved session (if has)
        $data = $this->getRequest()->getSession()->get("FormData.{$form->getName()}.data");

        return $data ? $form->loadDataFrom($data) : $form;
    }

    /**
     * Hanle payment form when submitted
     *
     * @param $data Submitted data
     * @param $form Original form
     * @return \SilverStripe\Control\HTTPResponse
     */
    public function makePayment($data, $form) {
        // save data to session
        $session = $this->getRequest()->getSession();
        $session->set("FormData.{$form->getName()}.data", $data);

        // received amount number
        $amount = $data['bt-amount'];
        // and payment method nonce sent from client
        $nonce = $data['bt-payment_method_nonce'];

        return $this->processPayment($session, $form, $nonce, $amount);
    }

    public function processPayment($session, $form, $nonce, $amount) {
        $gateway = BraintreeExtension::BTGateway();
        // make a transaction
        $result = $gateway->transaction()->sale([
            'amount' => $amount,
            'paymentMethodNonce' => $nonce,
            'options' => [
                'submitForSettlement' => true
            ]
        ]);

        if ($result->success || !is_null($result->transaction)) {
            // clear session if everything is fine
            $session->clear("FormData.{$form->getName()}.data");
            $form->sessionMessage('A payment of ' . $amount . '$ has been made!', 'Success');
        } else {
            // ERROR
            $errorString = "";

            foreach ($result->errors->deepAll() as $error) {
                $errorString .= 'Error: ' . $error->code . ": " . $error->message . "\n";
            }

            $form->sessionError('Unable to make a payment! ' . $errorString, 'Failure');
        }

        return $this->redirectBack();
    }

    /**
     * Generate SilverStripe to host a Braintree Drop UI form
     *
     * @return Braintree form using Dropin UI but disable 'choose another way to pay'
     */
    public function BTEditPaymentForm() {
        $gateway = BraintreeExtension::BTGateway();
        $member = Security::getCurrentUser();

        $form = Form::create(
            $this,
            __FUNCTION__,
            // <div> block to inject Braintree Drop UI, hidden field to store method nonce, hidden field to store how many payment methods in the vault
            FieldList::create(
                LiteralField::create('', '<div id="bted-dropin" class="js-bted-dropin"></div>'),
                HiddenField::create('bted-payment_method_nonce', '')
                    ->addExtraClass('js-bted-nonce'),
                HiddenField::create('bted-payment_methods-length', '')
                    ->addExtraClass('js-bted-methods-length')
            ),
            // Buttont to remove existing payment method or add a new card info as a new payment method
            FieldList::create(
                FormAction::create('editPayment','...')
                    ->setUseButtonTag(true)
                    ->addExtraClass('btn btn-default-color btn-sm js-bt-button-edit-payment')
            ),
            //
            null
        )
        ->addExtraClass('js-bted-payment-form')
        // client token as data attribute
        ->setAttribute('data-client-token', BraintreeExtension::BTClientToken($gateway, $member));

        return $form;
    }

    /**
     * Handle edit payment methods form
     *
     * @param $data Submitted data
     * @param $form The original form
     * @return \SilverStripe\Control\HTTPResponse
     * @throws \SilverStripe\ORM\ValidationException
     */
    public function editPayment($data, $form) {
        // received nonce and how many payment methods in the vault
        $nonce = $data['bted-payment_method_nonce'];
        $length = $data['bted-payment_methods-length'];

        // create a new payment method, prevent duplicate to get the method token
        $gateway = BraintreeExtension::BTGateway();
        $member = Security::getCurrentUser();
        $result = $gateway->paymentMethod()->create([
            'customerId' => BraintreeExtension::BTClientId($gateway, $member),
            'paymentMethodNonce' => $nonce,
            'options' => [
                'failOnDuplicatePaymentMethod' => true
            ]
        ]);

        // remove this payment method if this is a removal command
        if ($result->success || !is_null($result->transaction)) {
            if ($length != 0) {
                $gateway->paymentMethod()->delete($result->paymentMethod->token);
            }
        }

        return $this->redirectBack();
    }

    /**
     * @return Form
     * @throws \SilverStripe\ORM\ValidationException
     */
    public function BTPreviousTransactionsForm() {
        $gateway = BraintreeExtension::BTGateway();
        $member = Security::getCurrentUser();

        $collection = $gateway->transaction()->search([
            Braintree_TransactionSearch::customerId()->is(BraintreeExtension::BTClientId($gateway, $member)),
        ]);

        $transactions = ArrayList::create();

        foreach ($collection as $transaction) {
            $bttransaction = new BraintreeTransaction($transaction);
            $transactions->push($bttransaction);
        }

        // columns to display for transaction
        $dataColumns = new GridFieldDataColumns();
        $dataColumns->setDisplayFields(array(
            'Date' => 'Date',
            'Amount' => 'Amount',
            'Currency' => 'Currency',
            'Type' => 'Type',
            'Status' => 'Status',
        ));

        $config = GridFieldConfig::create();
        $config->addComponent(new GridFieldToolbarHeader())
            ->addComponent(new GridFieldSortableHeader())
            ->addComponent(new GridFieldPaginator(10))
            ->addComponent($dataColumns);

        $grid = new GridField('transactions', 'Previous Transactions', $transactions, $config);

        return new Form($this, __FUNCTION__, new FieldList($grid), new FieldList());
    }

}