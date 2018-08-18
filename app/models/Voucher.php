<?php
/**
 * Created by PhpStorm.
 * User: kasalirazaq
 * Date: 8/12/18
 * Time: 7:20 AM
 */

namespace App\Model;

class Voucher extends BaseModel
{

    public function Recipient()
    {
        return $this->belongsTo(Recipient::class, 'recipient_id');
    }

    public function SpecialOffer()
    {
        return $this->belongsTo(SpecialOffer::class);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code', 'expiry_date', 'expire_interval', 'date_of_usage'
    ];

    protected $hidden = ['id'];
    protected $table = 'vouchers';

    public function getExpiryDate()
    {
        return $this->expiry_date;
    }

    public function getIsUsed()
    {
        return $this->is_used;
    }

    public function getDateOfUsage()
    {
        return $this->date_of_usage;
    }

    public function getCode()
    {
        return $this->date_of_usage;
    }
}