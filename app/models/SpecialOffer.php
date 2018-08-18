<?php
/**
 * Created by PhpStorm.
 * User: kasalirazaq
 * Date: 8/12/18
 * Time: 7:39 AM
 */

namespace App\Model;


class SpecialOffer extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'discount',
    ];

    protected $hidden = ['id'];
    protected $table = 'special_offers';
}