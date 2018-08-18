<?php
/**
 * Created by PhpStorm.
 * User: kasalirazaq
 * Date: 8/17/18
 * Time: 11:58 AM
 */

use App\Util\Util;
use Carbon\Carbon;
use App\Helper\VoucherHelper;
use App\Helper\RecipientHelper;
use App\Helper\SpecialOfferHelper;

class VoucherUTest extends BaseTest
{
    public function testValidateRequest()
    {
        $response = Util::validateRequest(['email' => 'aloz@gmail.com'], [
            'email' => 'required|valid_email'
        ]);

        $this->assertArrayHasKey('status', $response);

        $this->assertTrue($response['status']);
    }

    public function testFailValidateRequest()
    {
        $response = Util::validateRequest(['email' => 'alozgmail.com'], [
            'email' => 'required|valid_email'
        ]);

        $this->assertArrayHasKey('status', $response);

        $this->assertFalse($response['status']);
    }

    public function testGenerateCodes()
    {
        $recipient = new RecipientHelper();
        $recipient->get('aloz@gmail.com', 'email');

        $offer = new SpecialOfferHelper();
        $offer->get('1', 'id');

        $voucher = new VoucherHelper();
        $expiryDate = Carbon::now()->addSeconds(10000)->toDateTimeString();
        $voucher->generate($offer->getOffer()->id, $recipient->getRecipient()->id, $expiryDate, 10000);
        $this->assertTrue($voucher->exist());
    }

    public function testVoucherExist()
    {
        $voucher = new VoucherHelper();
        $voucher->get(['id' => 1]);

        $this->assertTrue($voucher->exist());
    }

    public function testVoucherHasExpired()
    {
        $voucher = new VoucherHelper();
        $voucher->get(['id' => 1]);

        $this->assertTrue($voucher->hasExpired());
    }

    public function testVoucherIsUsed()
    {
        $voucher = new VoucherHelper();
        $voucher->get(['id' => 1]);

        $this->assertTrue($voucher->isUsed());
    }

    public function testGenerateCodeNotLessThan8()
    {
        $response = Util::generateRandomShortCode(8);

        $this->assertGreaterThanOrEqual(strlen($response), 8);
    }
}