<?php
/**
 * Created by PhpStorm.
 * User: kasalirazaq
 * Date: 8/16/18
 * Time: 3:51 PM
 */

use App\Helper\SpecialOfferHelper;

class VoucherFTest extends BaseTest
{
    public function testAddRecipient()
    {
        $faker = Faker\Factory::create();
        $info = new stdClass();
        $info->name = $faker->name;
        $info->email = $faker->email;
        $this->setFaker($info);
        $response = $this->runApp('Post', '/v1/user', ['name' => 'ade', 'email' => $this->getFaker()->email]);

        $this->assertSuccess($response);
    }

    public function testFailAddRecipient()
    {
        $response = $this->runApp('Post', '/v1/user', ['name' => 'ade', 'email' => $this->getFaker()->name]);

        $this->assertFail($response);
    }

    public function testAddSpecialOffer()
    {
        $response = $this->runApp('Post', '/v1/special/offer', ['name' => $this->getFaker()->name, 'discount' => 5]);

        $this->assertSuccess($response);
    }

    public function testGenerateVoucher()
    {
        $specialOffer = new SpecialOfferHelper();

        $specialOffer->get($this->getFaker()->name, 'name');
        $response = $this->runApp('Post', '/v1/voucher', ['special_offer_id' => $specialOffer->getOffer()->id, 'expires_in' => 60000]);

        $this->assertSuccess($response);
    }

    public function testFailGenerateVoucher()
    {
        $specialOffer = new SpecialOfferHelper();

        $specialOffer->get($this->getFaker()->name, 'name');
        $response = $this->runApp('Post', '/v1/voucher', ['special_offer_id' => $specialOffer->getOffer()->name, 'expires_in' => 10000]);

        $this->assertFail($response);
    }

    public function testGetCodes()
    {
        $email = $this->getFaker()->email;
        $response = $this->runApp('Get', "/v1/user/$email/voucher");

        $this->assertSuccess($response);
    }

    public function testRedeemVoucher()
    {
        $response = $this->runApp('Post', '/v1/voucher/redeem', ['voucher_code' => $this->getFaker()->voucher_code, 'email' => $this->getFaker()->email]);

        $this->assertSuccess($response);
    }

    public function testFailRedeemVoucher()
    {
        $response = $this->runApp('Post', '/v1/voucher/redeem', ['voucher_code' => $this->getFaker()->voucher_code, 'email' => 's@gmail.com']);

        $this->assertFail($response);
    }

    private function assertSuccess($response)
    {
        $responseJson = json_decode($response->getBody());

        $this->assertJson((string) $response->getBody());

        $this->assertObjectHasAttribute('status', $responseJson);

        $this->assertEquals(200, $response->getStatusCode());

        $this->assertSame('success', $responseJson->status);

        if (isset($responseJson->data) && is_array($responseJson->data) && isset($responseJson->data[0]->voucher_code)) {
            $info = $this->getFaker();
            $info->voucher_code = $responseJson->data[0]->voucher_code;
            $this->setFaker($info);
        }
    }

    private function assertFail($response)
    {
        $responseJson = json_decode($response->getBody());

        $this->assertJson((string) $response->getBody());

        $this->assertObjectHasAttribute('status', $responseJson);

        $this->assertGreaterThanOrEqual(400, $response->getStatusCode());
    }
}