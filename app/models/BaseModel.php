<?php
/**
 * Created by PhpStorm.
 * User: kasalirazaq
 * Date: 8/12/18
 * Time: 7:39 AM
 */

namespace App\Model;


use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    public static function getByFirstField($field, $value)
    {
        return self::where($field, $value)->first();
    }

    public static function getById($value)
    {
        return self::where('id', $value)->first();
    }

    /**
     * This is to assign the corresponding  array key value to model key
     * @param $data 'must be associative array'
     * @param array $filter 'filter field you do not want to set'
     * @return $this
     */
    public function setArrayValueToField(array $data, $filter = [])
    {
        foreach ($data as $key => $value) {
            if (!in_array($key, $filter)) {
                $this->$key = $value;
            }
        }

        return $this;
    }

    public static function getByFirstFields(array $data)
    {
        return self::where($data)->first();
    }
}