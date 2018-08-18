<?php
/**
 * Created by PhpStorm.
 * User: kasalirazaq
 * Date: 8/18/18
 * Time: 11:39 AM
 */

use App\Helper\RecipientHelper;
use App\Helper\SpecialOfferHelper;
use App\Helper\VoucherHelper;

$container = $app->getContainer();

$container["recipientHelper"] = function ($container) {
    return new RecipientHelper();
};

$container["specialOfferHelper"] = function ($container) {
    return new SpecialOfferHelper();
};

$container["voucherHelper"] = function ($container) {
    return new VoucherHelper();
};

