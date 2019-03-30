<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/2/3
 * Time: 23:27
 */
namespace App\Controllers;

use App\Services\IndexService;

class IndexController extends BaseController
{
    public function index()
    {
        $this->request->getRequest();
        $indexService = new IndexService();
        $out = $indexService->index();
        $this->response->json($out);
    }
}