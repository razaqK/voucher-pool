<?php
/**
 * Created by PhpStorm.
 * User: kasalirazaq
 * Date: 8/17/18
 * Time: 3:47 AM
 */

namespace App\Runner;

class App
{
    private $app;

    public function __construct($env = null)
    {
        if ($env == 'test') {
            $config = include __DIR__ . '/../app/config/config_test.php';
        }else {
            $config = include __DIR__ . '/../app/config/config.php';
        }

        //instantiate the App object
        $app = new \Slim\App($config);

        include __DIR__ . '/../app/config/db.php';

        include __DIR__ . '/../app/config/error.php';

        include __DIR__ . '/../app/routes/v1_api.php';

        $this->app = $app;
    }

    public function get()
    {
        return $this->app;
    }
}