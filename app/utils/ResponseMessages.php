<?php
/**
 * Created by PhpStorm.
 * User: kasalirazaq
 * Date: 8/12/18
 * Time: 2:34 PM
 */

namespace App\Util;


class ResponseMessages
{
    const SUCCESSFUL = 'Successful';
    const INVALID_PARAMS = 'Bad request!. The parameters sent are not according to the api spec. %s';
    const INTERNAL_SERVER_ERROR = 'There was an error processing your request. Please try again later.';
    const ERROR_CREATING = 'An error occurred. %s';
    const NOT_FOUND = 'Seems %s can not be found';
    const VOUCHER_CODE_ERROR = 'Seems the voucher code %s';
    const USER_EXIST = 'The user account already exist';
}