<?php
/**
 * Created by PhpStorm.
 * User: kasalirazaq
 * Date: 8/12/18
 * Time: 4:24 AM
 */

namespace App\Controller;

use App\Util\Util;
use Psr\Container\ContainerInterface;
use App\Traits\ResponseTrait;
use Slim\Http\Request;
use Slim\Http\Response;

class BaseController
{
    use ResponseTrait;

    protected $_container;
    protected $_request;
    /** @var Response */
    protected $_response;

    public function __construct(ContainerInterface $container)
    {
        $this->setContainer($container);
    }

    private function setContainer($container)
    {
        $this->_container = $container;
    }

    public function getContainer() : ContainerInterface
    {
        return $this->_container;
    }

    /**
     * @return Request
     */
    public function request() : Request
    {
        return $this->getContainer()->get('request');
    }

    public function setResponse(Response $response)
    {
        $this->_response = $response;
    }

    /**
     * @return Response
     */
    public function response() : Response
    {
        return $this->_response;
    }

    /**
     * @param $payload
     * @param $roles
     * @return array
     */
    protected function validateParameters($payload, array $roles)
    {
        return Util::validateRequest($payload, $roles);
    }
}