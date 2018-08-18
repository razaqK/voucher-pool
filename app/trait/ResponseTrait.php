<?php
/**
 * Created by PhpStorm.
 * User: kasalirazaq
 * Date: 8/12/18
 * Time: 11:56 AM
 */

namespace App\Traits;

trait ResponseTrait
{
    protected function sendSuccess(array $data, $status = 200)
    {
        return $this->response()->withStatus($status)->withJson(['status' => 'success', 'data' => $data]);
    }

    protected function sendError($message, $code, $status = 500, $trace = null)
    {
        return $this->response()->withStatus($status)->withJson(['status' => 'error', 'message' => $message, 'code' => $code, 'trace' => $trace]);
    }

    protected function sendFail($message, $code, $status = 400, $trace = null)
    {
        return $this->response()->withStatus($status)->withJson(['status' => 'fail', 'message' => $message, 'code' => $code, 'trace' => $trace]);
    }
}