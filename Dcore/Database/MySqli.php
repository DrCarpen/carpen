<?php
/**
 * @author liyang <liyang@uniondrug.cn>
 * @date   2019-03-23
 */
namespace Dcore\Database;

class MySqli implements DbInterface
{
    private $host; //数据库主机
    private $user; //数据库用户名
    private $password; //数据库用户名密码
    private $dataBaseName; //数据库名
    private $port; //端口号
    private $coding; //数据库编码，GBK,UTF8,gb2312
    private $connect;

    public function __construct($host, $user, $password, $dataBaseName, $port = 3306, $coding = 'UTF8')
    {
        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
        $this->dataBaseName = $dataBaseName;
        $this->coding = $coding;
        $this->port = $port;
        $this->connect();
        $this->setUtf8();
    }

    /**
     * 数据库连接
     */
    public function connect()
    {
        $this->connect = mysqli_connect($this->host, $this->user, $this->password, $this->dataBaseName, $this->port);
    }

    /**
     * @param $sql
     * @return array|mixed
     */
    public function queryOne($sql)
    {
        $result = mysqli_query($this->connect, $sql);
        $arr = [];       //定义空数组
        while ($row = mysqli_fetch_array($result)) {
            array_push($arr, $row);
        }
        return $arr;
    }

    /**
     * @param $sql
     * @return array|mixed
     */
    public function queryAll($sql)
    {
        $result = mysqli_query($this->connect, $sql);
        $arr = [];       //定义空数组
        while ($row = mysqli_fetch_array($result)) {
            array_push($arr, $row);
        }
        return $arr;
    }

    /**
     * 设置字符
     */
    public function setUtf8()
    {
        mysqli_query($this->connect, 'set names utf8');
    }
}