<?php
/**
 * Created by PhpStorm.
 * User: kasalirazaq
 * Date: 8/12/18
 * Time: 7:38 AM
 */

namespace App\Model;


class Recipient extends BaseModel
{
    public function Voucher()
    {
        return $this->hasMany(Voucher::class, 'id');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email',
    ];

    protected $hidden = ['id'];
    protected $table = 'recipients';
}