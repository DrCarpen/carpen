<?php
/**
 * @author liyang <liyang@uniondrug.cn>
 * @date   2019-03-24
 */
namespace Dcore\Response;

class Response implements ResponseInterface
{
    public function outPut($data)
    {
        return [
            'code' => 400,
            'msg' => 'ok',
            'data' => $data
        ];
    }

    public function json($data)
    {
        echo json_encode($this->outPut($data), 256);
    }
}