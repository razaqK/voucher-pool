<?php
/**
 * Created by PhpStorm.
 * User: kasalirazaq
 * Date: 8/12/18
 * Time: 7:51 AM
 */

namespace App\Helper;


use App\Model\Recipient;

class RecipientHelper
{
    private $recipient;

    /**
     * @param $name
     * @param $email
     * @return mixed
     * @throws \Exception
     */
    public function add(string $name, string $email) : self
    {
        try {
            /** @var Recipient $recipient */
            $recipient = new Recipient();
            $checkExistence = Recipient::getByFirstField('email', $email);
            if ($checkExistence) {
                return false;
            }

            $toAdd = [
                'name' => strtolower($name),
                'email' => $email
            ];

            $recipient->setArrayValueToField($toAdd)->save();
            $recipient->refresh();
            $this->setRecipient($recipient);
            return $this;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * @param $value
     * @param $field
     * @return $this
     */
    public function get($value, string $field) : self
    {
        $recipient = Recipient::getByFirstField($field, $value);
        $this->setRecipient($recipient);
        return $this;
    }

    /**
     * @param Recipient $recipient
     */
    private function setRecipient($recipient)
    {
        $this->recipient = $recipient;
    }

    /**
     * @return Recipient
     */
    public function getRecipient()
    {
        return $this->recipient;
    }

    /**
     * @return bool
     */
    public function exist() : bool
    {
        return $this->getRecipient() instanceof Recipient;
    }
}