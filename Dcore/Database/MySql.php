<?php
/**
 * @author liyang <liyang@uniondrug.cn>
 * @date   2019-03-23
 */
namespace Dcore\Database;

class MySql implements DbInterface
{
    private $host; //数据库主机
    private $user; //数据库用户名
    private $password; //数据库用户名密码
    private $dataBaseName; //数据库名
    private $port; //端口号
    private $coding; //数据库编码，GBK,UTF8,gb2312

    /**
     * 构造函数
     * MySql constructor.
     * @param        $host
     * @param        $user
     * @param        $password
     * @param        $dataBaseName
     * @param int    $port
     * @param string $coding
     */
    public function __construct($host, $user, $password, $dataBaseName, $port = 3306, $coding = 'UTF8')
    {
        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
        $this->dataBaseName = $dataBaseName;
        $this->coding = $coding;
        $this->port = $port;
    }

    public function connect()
    {
    }

    public function query()
    {
    }

    public function queryOne()
    {
    }

    public function queryAll()
    {
    }
}
