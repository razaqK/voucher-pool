<?php
/**
 * Created by PhpStorm.
 * User: kasalirazaq
 * Date: 8/12/18
 * Time: 7:27 AM
 */

namespace App\Helper;

use App\Model\Recipient;
use App\Model\Voucher;
use App\Util\Status;
use App\Util\Util;
use Carbon\Carbon;
use Illuminate\Database\Capsule\Manager as DB;

class VoucherHelper
{
    private $voucher;

    /**
     * method to generate a single code
     * @param $offerId
     * @param $recipientId
     * @param $expireDate
     * @param $expireInterval
     * @return mixed
     * @throws \Exception
     */
    public function generate($offerId, $recipientId, $expireDate, $expireInterval)
    {
        try {
            $code = new Voucher();

            $toAdd = [
                'code' => Util::generateRandomShortCode(8),
                'special_offer_id' => $offerId,
                'recipient_id' => $recipientId,
                'expiry_date' => $expireDate,
                'expire_interval' => $expireInterval
            ];

            $code->setArrayValueToField($toAdd)->save();
            $code->refresh();
            $this->setVoucher($code);
            return $this;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * the method is to get voucher
     * @param array $data
     * @return $this
     * @throws \Exception
     */
    public function get(array $data)
    {
        $voucher = Voucher::getByFirstFields($data);
        if (!$voucher) {
            throw new \Exception('Seems the voucher is invalid.');
        }
        $this->setVoucher($voucher);
        return $this;
    }

    /**
     * internal method to set voucher
     * @param $voucher
     */
    private function setVoucher($voucher)
    {
        $this->voucher = $voucher;
    }

    /**
     * @return Voucher
     */
    public function getVoucher()
    {
        return $this->voucher;
    }

    /**
     * check if voucher has expired
     * @return bool
     */
    public function hasExpired()
    {
        if ($this->getVoucher()->status == Status::EXPIRED) {
            return true;
        }
        $now = Carbon::now();
        $expiryDate = new Carbon($this->getVoucher()->getExpiryDate());
        if (!$expiryDate->lt($now)) {
            return false;
        }
        $this->markAsExpired();
        return true;
    }

    /**
     * internal method for marking voucher as expired
     */
    private function markAsExpired()
    {
        $this->getVoucher()->status = Status::EXPIRED;
        $this->getVoucher()->save();
    }

    /**
     * check if voucher code has been used
     * @return bool
     */
    public function isUsed()
    {
        return (bool)$this->getVoucher()->getIsUsed();
    }

    /**
     * internal method for redeeming voucher by updating the usage state, status and date of usage
     */
    private function updateVoucherState()
    {
        $this->getVoucher()->is_used = 1;
        $this->getVoucher()->status = Status::DISABLED;
        $this->getVoucher()->date_of_usage = Carbon::now()->toDateTimeString();
        $this->getVoucher()->save();
    }

    /**
     * method to redeem voucher
     */
    public function redeem()
    {
        $this->updateVoucherState();
    }

    /**
     *
     * @return bool
     */
    public function exist()
    {
        return $this->getVoucher() instanceof Voucher;
    }

    /**
     * generate voucher codes for all active recipient
     * @param $offerId
     * @param $expireDate
     * @param $expireInterval
     * @return bool
     * @throws \Exception
     */
    public function generateCodeForActiveRecipients($offerId, $expireDate, $expireInterval)
    {
        $recipients = Recipient::where(['status' => Status::ACTIVE])->get();
        if (empty($recipients)){
            return false;
        }

        foreach ($recipients as $recipient) {
            $this->generate($offerId, $recipient->id, $expireDate, $expireInterval);
        }

        return true;
    }

    /**
     * get user specific voucher code
     * @param $email
     * @param int $offset
     * @param int $limit
     * @return \Illuminate\Support\Collection
     */
    public function getUserCodes($email, $offset = 0, $limit = 1000)
    {
        $query = DB::table('vouchers')
            ->join('recipients', 'vouchers.recipient_id', '=', 'recipients.id')
            ->join('special_offers', 'vouchers.special_offer_id', '=', 'special_offers.id')
            ->select('special_offers.name as special_offer_name', 'special_offers.discount as discount_percentage', 'vouchers.code as voucher_code', 'recipients.email', 'vouchers.date_of_usage', 'vouchers.is_used', 'vouchers.expiry_date')
            ->where([['recipients.email', '=', $email], ['vouchers.status', '!=', Status::DISABLED]])
            ->offset($offset)
            ->limit($limit);

        return $query->get();
    }

    /**
     * get all voucher codes with special offers
     * @param int $offset
     * @param int $limit
     * @return \Illuminate\Support\Collection
     */
    public function getAll($offset = 0, $limit = 1000)
    {
        $query = DB::table('vouchers')
            ->join('recipients', 'vouchers.recipient_id', '=', 'recipients.id')
            ->join('special_offers', 'vouchers.special_offer_id', '=', 'special_offers.id')
            ->select('special_offers.id as special_offer_id', 'special_offers.name as special_offer_name', 'special_offers.discount as discount_percentage', 'vouchers.code as voucher_code', 'recipients.email', 'vouchers.date_of_usage', 'vouchers.is_used', 'vouchers.expiry_date')
            ->offset($offset)
            ->limit($limit);

        return $query->get();
    }
}