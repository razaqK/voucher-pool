<?php
/**
 * Created by PhpStorm.
 * User: kasalirazaq
 * Date: 8/11/18
 * Time: 7:01 PM
 */

require __DIR__ . '/../vendor/autoload.php';

$app = (new \App\Runner\App())->get();

// Run application
$app->run();