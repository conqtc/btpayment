<?php
namespace AlexT;

use SilverStripe\ORM\DataExtension;

/**
 * Class BraintreeMemberExtension
 *
 * Extend SilverStripe data to include a Braintree customer id
 * @package SilverStripe\Lessons
 */
class BraintreeMemberExtension extends DataExtension {
    /**
     * {@inheritdoc}
     */
    private static $db = [
        "BTClientId" => "Varchar"
    ];
}
