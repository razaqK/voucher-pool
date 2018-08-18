<?php
/**
 * Created by PhpStorm.
 * User: kasalirazaq
 * Date: 8/12/18
 * Time: 4:24 AM
 */

namespace App\Controller;

use App\Helper\RecipientHelper;
use App\Helper\SpecialOfferHelper;
use App\Helper\VoucherHelper;
use App\Util\ResponseCodes;
use App\Util\ResponseMessages;
use App\Util\Util;
use Slim\Http\Response;
use Slim\Http\Request;
use Carbon\Carbon;

class VoucherController extends BaseController
{
    /**
     * The method handle creation of special offer
     * @param Request $request
     * @param Response $response
     * @return mixed
     */
    public function createSpecialOffer(Request $request, Response $response)
    {
        try {
            $this->setResponse($response);
            $payload = $request->getParsedBody();

            // validate request payload
            $valid = $this->validateParameters($payload, [
                'name' => 'required|is_string',
                'discount' => 'required|integer'
            ]);

            if (!$valid['status']) {
                return $this->sendFail(sprintf(ResponseMessages::INVALID_PARAMS, $valid['message']), ResponseCodes::INVALID_PARAMS, 400, $valid['data']);
            }

            $this->getContainer()->specialOfferHelper->add($payload['name'], $payload['discount']); // create special offer

            return $this->sendSuccess(['name' => $this->getContainer()->specialOfferHelper->getOffer()->name]);
        } catch (\Throwable $exception) {
            return $this->sendError(ResponseMessages::INTERNAL_SERVER_ERROR, ResponseCodes::INTERNAL_SERVER_ERROR, 500, $exception->getMessage());
        }
    }

    /**
     * The method is to create recipient
     * @param Request $request
     * @param Response $response
     * @return mixed
     */
    public function createUser(Request $request, Response $response)
    {
        try {
            $this->setResponse($response);
            $payload = $request->getParsedBody();
            // validate request payload
            $valid = $this->validateParameters($payload, [
                'name' => 'required|is_string',
                'email' => 'required|valid_email'
            ]);

            if (!$valid['status']) {
                return $this->sendFail(sprintf(ResponseMessages::INVALID_PARAMS, $valid['message']), ResponseCodes::INVALID_PARAMS, 400, $valid['data']);
            }

            // check if user already exist
            if ($this->getContainer()->recipientHelper->get($payload['email'], 'email')->exist()) {
                return $this->sendFail(ResponseMessages::USER_EXIST, ResponseCodes::USER_EXIST, 400);
            }
            $this->getContainer()->recipientHelper->add($payload['name'], $payload['email']);

            return $this->sendSuccess([]);
        } catch (\Throwable $exception) {
            return $this->sendError(ResponseMessages::INTERNAL_SERVER_ERROR, ResponseCodes::INTERNAL_SERVER_ERROR, 500, $exception->getMessage());
        }
    }

    /**
     * The method is to generate voucher code for all recipients
     * @param Request $request
     * @param Response $response
     * @return mixed
     */
    public function generateCodeForRecipients(Request $request, Response $response)
    {
        try {
            $this->setResponse($response);

            $payload = $request->getParsedBody();
            // validate request payload
            $valid = $this->validateParameters($payload, [
                'special_offer_id' => 'required|integer',
                'expires_in' => 'required|integer'
            ]);

            if (!$valid['status']) {
                return $this->sendFail(sprintf(ResponseMessages::INVALID_PARAMS, $valid['message']), ResponseCodes::INVALID_PARAMS, 400, $valid['data']);
            }

            // checking if it is a valid special offer
            if (!$this->getContainer()->specialOfferHelper->get($payload['special_offer_id'], 'id')->exist()) {
                return $this->sendFail(sprintf(ResponseMessages::INVALID_PARAMS, 'The special offer id supplied is invalid'), ResponseCodes::INVALID_PARAMS, 400);
            }

            $this->getContainer()->voucherHelper->generateCodeForActiveRecipients(
                $this->getContainer()->specialOfferHelper->getOffer()->id,
                Carbon::now()->addSeconds($payload['expires_in'])->toDateTimeString(),
                $payload['expires_in']
            );

            return $this->sendSuccess([]);
        } catch (\Throwable $exception) {
            return $this->sendError(ResponseMessages::INTERNAL_SERVER_ERROR, ResponseCodes::INTERNAL_SERVER_ERROR, 500, $exception->getMessage());
        }
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return mixed
     */
    public function generateCode(Request $request, Response $response)
    {
        try {
            $this->setResponse($response);
            $payload = $request->getParsedBody();
            // validate request payload
            $valid = $this->validateParameters($payload, [
                'special_offer_id' => 'required|integer',
                'email' => 'required|valid_email',
                'expires_in' => 'required|integer'
            ]);

            if (!$valid['status']) {
                return $this->sendFail(sprintf(ResponseMessages::INVALID_PARAMS, $valid['message']), ResponseCodes::INVALID_PARAMS, 400, $valid['data']);
            }

            // check if recipient exist
            if (!$this->getContainer()->recipientHelper->get($payload['email'], 'email')->exist()) {
                return $this->sendFail(sprintf(ResponseMessages::NOT_FOUND, 'user account'), ResponseCodes::NOT_FOUND, 404);
            }

            // check if the special offer is valid
            if (!$this->getContainer()->specialOfferHelper->get($payload['special_offer_id'], 'id')->exist()) {
                return $this->sendFail(sprintf(ResponseMessages::INVALID_PARAMS, 'The special offer id supplied is invalid'), ResponseCodes::INVALID_PARAMS, 400);
            }

            // generate voucher code with expiry date
            $this->getContainer()->voucherHelper->generate(
                $payload['special_offer_id'],
                $this->getContainer()->recipientHelper->getRecipient()->id,
                Carbon::now()->addSeconds($payload['expires_in'])->toDateTimeString(),
                $payload['expires_in']
            );

            return $this->sendSuccess([
                'code' => $this->getContainer()->voucherHelper->getVoucher()->getCode(),
                'expiry_date' => $this->getContainer()->voucherHelper->getVoucher()->getExpiryDate()
            ]);
        } catch (\Throwable $exception) {
            return $this->sendError(ResponseMessages::INTERNAL_SERVER_ERROR, ResponseCodes::INTERNAL_SERVER_ERROR, 500, $exception->getMessage());
        }
    }

    /**
     * The method is to redeem voucher
     * @param Request $request
     * @param Response $response
     * @return mixed
     */
    public function redeemCode(Request $request, Response $response)
    {
        try {
            $this->setResponse($response);
            $payload = $request->getParsedBody();

            // validate request
            $valid = $this->validateParameters($payload, [
                'voucher_code' => 'required',
                'email' => 'required|valid_email'
            ]);

            if (!$valid['status']) {
                return $this->sendFail(sprintf(ResponseMessages::INVALID_PARAMS, $valid['message']), ResponseCodes::INVALID_PARAMS, 400, $valid['data']);
            }

            if (!$this->getContainer()->recipientHelper->get($payload['email'], 'email')->exist()) {
                return $this->sendFail(sprintf(ResponseMessages::NOT_FOUND, 'user account'), ResponseCodes::NOT_FOUND, 404);
            }

            if (!$this->getContainer()->voucherHelper->get(['code' => $payload['voucher_code'], 'recipient_id' => $this->getContainer()->recipientHelper->getRecipient()->id])->exist()) {
                return $this->sendFail(sprintf(ResponseMessages::VOUCHER_CODE_ERROR, 'provided is invalid'), ResponseCodes::VOUCHER_CODE_ERROR, 400);
            }

            if ($this->getContainer()->voucherHelper->isUsed()) {
                return $this->sendFail(sprintf(ResponseMessages::VOUCHER_CODE_ERROR, 'has been used.'), ResponseCodes::VOUCHER_CODE_ERROR, 400);
            }

            if ($this->getContainer()->voucherHelper->hasExpired()) {
                return $this->sendFail(sprintf(ResponseMessages::VOUCHER_CODE_ERROR, 'has expired'), ResponseCodes::VOUCHER_CODE_ERROR, 400);
            }

            // redeem code by update the status, usage and date of usage
            $this->getContainer()->voucherHelper->redeem();

            return $this->sendSuccess([
                'discount_percentage' => $this->getContainer()->voucherHelper->getVoucher()->specialOffer->discount,
                'date_of_usage' => $this->getContainer()->voucherHelper->getVoucher()->getDateOfUsage()
            ]);
        } catch (\Throwable $exception) {
            return $this->sendError(ResponseMessages::INTERNAL_SERVER_ERROR, ResponseCodes::INTERNAL_SERVER_ERROR, 500, $exception->getMessage());
        }
    }

    /**
     * The method is to get user specific voucher codes. It accept the page and limit as query params
     * @param Request $request
     * @param Response $response
     * @param $email
     * @return mixed
     */
    public function getUserCodes(Request $request, Response $response, $email)
    {
        try {
            $this->setResponse($response);

            $query = $request->getQueryParams();
            list($offset, $limit) = Util::getOffsetAndLimit($query['page'], $query['limit']);

            $result = $this->getContainer()->voucherHelper->getUserCodes($email, $offset, $limit);

            if (empty($result->toArray())) {
                return $this->sendFail(sprintf(ResponseMessages::NOT_FOUND, 'data'), ResponseCodes::NOT_FOUND, 400);
            }

            return $this->sendSuccess($result->toArray());
        } catch (\Throwable $exception) {
            return $this->sendError(ResponseMessages::INTERNAL_SERVER_ERROR, ResponseCodes::INTERNAL_SERVER_ERROR, 500, $exception->getMessage());
        }
    }

    /**
     * This method is to get all active voucher codes
     * @param Request $request
     * @param Response $response
     * @return mixed
     */
    public function getAllVoucher(Request $request, Response $response)
    {
        try {
            $this->setResponse($response);

            $query = $request->getQueryParams();
            list($offset, $limit) = Util::getOffsetAndLimit($query['page'], $query['limit']);

            $result = $this->getContainer()->voucherHelper->getAll($offset, $limit);

            if (empty($result->toArray())) {
                return $this->sendFail(sprintf(ResponseMessages::NOT_FOUND, 'data'), ResponseCodes::NOT_FOUND, 400);
            }

            return $this->sendSuccess($result->toArray());
        } catch (\Throwable $exception) {
            return $this->sendError(ResponseMessages::INTERNAL_SERVER_ERROR, ResponseCodes::INTERNAL_SERVER_ERROR, 500, $exception->getMessage());
        }
    }
}