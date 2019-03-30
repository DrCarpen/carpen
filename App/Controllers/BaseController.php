<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/2/3
 * Time: 23:27
 */
namespace App\Controllers;

class BaseController
{
    public $response;
    public $request;

    public function __construct()
    {
        $container = DI();
        $this->response = $container->get('Dcore\Response\Response');
        $this->request = $container->get('Dcore\Request\Request');
    }
}