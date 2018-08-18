<?php
/**
 * Created by PhpStorm.
 * User: kasalirazaq
 * Date: 8/16/18
 * Time: 4:21 PM
 */

use App\Runner\App;
use Slim\Http\Environment;
use Slim\Http\Response;
use Slim\Http\Request;
use PHPUnit\Framework\TestCase;

use Illuminate\Database\Capsule\Manager as DB;

class BaseTest extends TestCase
{
    private static $app;
    static $specialOfferName = null;
    static $info = null;

    public static function setUpBeforeClass()
    {
        self::$app = (new App('test'))->get();
        DB::connection()->unprepared(file_get_contents(__DIR__ . '/data/dump.sql'));
    }

    public function testAppInstance()
    {
        $this->assertTrue(self::$app instanceof Slim\App, "Testing if app is a valid instance of slim app");
    }

    public function runApp($requestMethod, $requestUri, $requestData = null)
    {
        // Create a mock environment for testing with
        $environment = Environment::mock([
            'REQUEST_METHOD' => $requestMethod,
            'REQUEST_URI' => $requestUri
        ]);

        // Set up a request object based on the environment
        $request = Request::createFromEnvironment($environment);

        // Add request data, if it exists
        if (isset($requestData)) {
            $request = $request->withParsedBody($requestData);
        }

        // Set up a response object
        $response = new Response();

        // Process the application
        $response = self::$app->process($request, $response);

        return $response;
    }

    private static function truncate()
    {
        DB::statement("SET foreign_key_checks=0");
        $databaseName = DB::getDatabaseName();
        $tables = DB::select("SELECT * FROM information_schema.tables WHERE table_schema = '$databaseName'");
        foreach ($tables as $table) {
            $name = $table->TABLE_NAME;
            //if you don't want to truncate migrations
            if ($name == 'phinxlog') {
                continue;
            }
            DB::table($name)->truncate();
        }

        DB::statement("SET foreign_key_checks=1");
    }

    public static function tearDownAfterClass()
    {
       self::truncate();
    }

    public function setFaker($faker)
    {
        self::$info = $faker;
    }

    public function getFaker()
    {
        return self::$info;
    }

    public function getValue()
    {
        return self::$specialOfferName;
    }

    public function setValue($value)
    {
        self::$specialOfferName = $value;
    }
}