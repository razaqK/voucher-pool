<?php
/**
 * Created by PhpStorm.
 * User: kasalirazaq
 * Date: 8/12/18
 * Time: 5:01 AM
 */

$container = $app->getContainer();
//boot eloquent connection
$capsule = new \Illuminate\Database\Capsule\Manager;

$capsule->addConnection($container['settings']['db']);

$capsule->setAsGlobal();

$capsule->bootEloquent();
//pass the connection to global container (created in previous article)

$container['db'] = function ($container) use ($capsule){
    return $capsule;
};