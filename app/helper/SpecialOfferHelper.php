<?php
/**
 * Created by PhpStorm.
 * User: kasalirazaq
 * Date: 8/12/18
 * Time: 8:38 AM
 */

namespace App\Helper;


use App\Model\SpecialOffer;

class SpecialOfferHelper
{
    private $offer;

    /**
     * @param $name
     * @param $discount
     * @return mixed
     * @throws \Exception
     */
    public function add($name, $discount)
    {
        try {
            $offer = new SpecialOffer();

            $toAdd = [
                'name' => strtolower($name),
                'discount' => $discount
            ];

            $offer->setArrayValueToField($toAdd)->save();
            $offer->refresh();
            $this->setOffer($offer);
            return $this;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * @param $value
     * @param $field
     * @return $this
     * @throws \Exception
     */
    public function get($value, $field)
    {
        $offer = SpecialOffer::getByFirstField($field, $value);
        if (!$offer) {
            throw new \Exception('The special offer id supplied is invalid');
        }
        $this->setOffer($offer);
        return $this;
    }

    /**
     * @param SpecialOffer $offer
     */
    private function setOffer($offer)
    {
        $this->offer = $offer;
    }

    /**
     * @return SpecialOffer
     */
    public function getOffer()
    {
        return $this->offer;
    }

    /**
     * @return bool
     */
    public function exist()
    {
        return $this->getOffer() instanceof SpecialOffer;
    }
}