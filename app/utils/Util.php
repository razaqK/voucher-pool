<?php
/**
 * Created by PhpStorm.
 * User: kasalirazaq
 * Date: 8/12/18
 * Time: 9:24 AM
 */

namespace App\Util;

class Util
{

    /**
     * validate request body by applying some roles
     * @param $payload
     * @param $roles
     * @return array
     */
    public static function validateRequest($payload, $roles)
    {
        $validated = GUMPHelper::is_valid((array)$payload, $roles);
        if ($validated === true) {
            return ['status' => true];
        }

        $messageList = Util::stripHtmlTags($validated);
        $message = implode(',', $messageList);

        return ['status' => false, 'message' => $message, 'data' => $messageList];
    }

    /**
     * Generates Random alphanumeric short code
     *
     * @param int $length
     * @param $toLower
     * @return string
     */
    public static function generateRandomShortCode($length = 5, $toLower = false)
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $toLower ? strtolower($randomString) : $randomString;
    }

    /**
     * Remove html tags from string
     * @param $params
     * @return array
     */
    public static function stripHtmlTags($params)
    {
        return array_map(function ($v) {
            return strtolower(strip_tags($v));
        }, $params);
    }

    /**
     * get offset from page and limit
     * @param $page
     * @param $limit
     * @return array
     */
    public static function getOffsetAndLimit($page, $limit)
    {
        $limit = empty($limit) || $limit <= 0 ? 1000 : $limit;
        $offset = empty($page) || $page <= 0 ? 0 : ($page - 1) * $limit;
        return [$offset, $limit];
    }
}