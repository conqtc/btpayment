<?php
namespace AlexT\BTPayment;

use Braintree\Gateway;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;
use SilverStripe\ORM\DataExtension;
use SilverStripe\Security\Member;
use SilverStripe\Security\Security;
use SilverStripe\SiteConfig\SiteConfig;

/**
 * Class BraintreeExtension
 * An extension of site configuration which adds Braintree Payment Settings in the admin page.
 * This class also provides some interface to get gateway, client id or create a customer in the vault if not exists
 *
 * @package SilverStripe\Lessons
 */
class BraintreeExtension extends DataExtension {
    // Gateway object will be created if null
    private static $gateway = null;

    /**
     * {@inheritdoc}
     */
    private static $db = [
        'BTEnvironment' => 'Varchar',
        'BTMerchantId' => 'Varchar',
        'BTPublicKey' => 'Varchar',
        'BTPrivateKey' => 'Varchar'
    ];

    /**
     * Update site configuration with 'Brain Tree Payment' tab which includes 4 setting fields for payment credentials
     *
     * @param FieldList $fields list of fields of site configuration
     */
    public function updateCMSFields(FieldList $fields) {
        $fields->addFieldsToTab('Root.BraintreePayment', array(
            DropdownField::create('BTEnvironment', 'Environment', array('sandbox' => 'sandbox', 'production' => 'production')),
            TextField::create('BTMerchantId', 'Merchant ID'),
            TextField::create('BTPublicKey', 'Public Key'),
            TextField::create('BTPrivateKey', 'Private Key')
        ));
    }

    /**
     * Get a Braintree Gateway object based on the Braintree credentials in the setting
     *
     * @return Gateway|null
     */
    public static function BTGateway() {
        // get current site config
        $siteConfig = SiteConfig::current_site_config();

        // create a new Gateway object if null
        if (self::$gateway == null) {
            self::$gateway = new Gateway([
                'environment' => $siteConfig->BTEnvironment,
                'merchantId' => $siteConfig->BTMerchantId,
                'publicKey' => $siteConfig->BTPublicKey,
                'privateKey' => $siteConfig->BTPrivateKey
            ]);
        }

        return self::$gateway;
    }

    /**
     * Generate client token based on customer id in the vault (should it exists)
     *
     * @param $customerId Customer Id stored in the vault
     * @return client token (string)
     */
    public static function BTClientToken($gateway, $member) {
        if ($gateway != null && $member != null) {
            // generate a client token from customer id
            return $gateway->ClientToken()->generate(["customerId" => self::BTClientId($gateway, $member)]);
        }

        return null;
    }

    /**
     * Get customer(client) id
     *
     * @return A string represents customer id
     * @throws \SilverStripe\ORM\ValidationException
     */
    public static function BTClientId($gateway, $member)
    {
        if ($member != null) {
            $btClientId = $member->BTClientId;
            // if customer is not created in the vault
            if (empty($btClientId) || is_null($btClientId)) {
                // create a new customer in the vault, return
                $btClientId = self::BTCreateClient($gateway, $member);
                // write back to database
                $member->BTClientId = $btClientId;
                $member->write();
            }

            return $btClientId;
        } else {
            return -1;
        }
    }

    /**
     * Create a new customer in the vault
     *
     * @param $gateway Braintree gateway
     * @param $member current SilverStripe member
     * @return new customer id
     */
    public static function BTCreateClient($gateway, $member) {
        if ($gateway != null && $member != null) {
            $result = $gateway->customer()->create([
                'firstName' => $member->FirstName,
                'lastName' => $member->SurName,
                /*
                'company' => '',
                'email' => '',
                'phone' => '',
                'fax' => '',
                'website' => ''
                */
            ]);

            if ($result->success) {
                return $result->customer->id;
            } else {
                return '';
            }
        } else {
            return '';
        }
    }
}