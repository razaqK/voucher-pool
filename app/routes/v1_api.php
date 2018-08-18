<?php
/**
 * Created by PhpStorm.
 * User: kasalirazaq
 * Date: 8/11/18
 * Time: 7:09 PM
 */

// Add route callbacks
$app->group('/v1', function () {
    $this->post('/special/offer', 'App\Controller\VoucherController:createSpecialOffer');
    $this->get('/user/{email}/voucher', 'App\Controller\VoucherController:getUserCodes');
    $this->post('/user', 'App\Controller\VoucherController:createUser');
    $this->post('/voucher', 'App\Controller\VoucherController:generateCodeForRecipients');
    $this->post('/voucher/redeem', 'App\Controller\VoucherController:redeemCode');
    $this->get('/vouchers', 'App\Controller\VoucherController:getAllVoucher');
});
