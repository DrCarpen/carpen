<?php
namespace Dcore\Command;

class Command
{
    /**
     *
     */
    public function init()
    {
        $this->debug($this->getArgv());
        $this->out($this->getArgv());
    }

    /**
     * 获取入参
     * @return mixed
     */
    private function getArgv()
    {
        $argv = $_SERVER['argv'];
        array_shift($argv);
        return $argv;
    }

    private function out($info)
    {
        foreach ($info as $key => $val) {
            echo $val.PHP_EOL;
            $this->debug($val);
        }
    }

    private function debug($info)
    {
        $string = 'log_time:'.date('Y-m-d H:i:s').json_encode($info, 256).PHP_EOL;
        file_put_contents('log/test.log', $string, FILE_APPEND);
    }
}
