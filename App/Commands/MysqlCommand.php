<?php
/**
 * 生成mysqli数据字典
 */

class MysqlDocs
{
    private $host = 'localhost';
    private $userName = 'root';
    private $dbPwd = 'root';
    private $dababase = 'test';
    private $table = 'wx_members';
    private $conn;
    private $noShowFields = []; //不需要显示的字段
    private $columns = [];
    private $int = [
        'int',
        'integer',
        'tinyint',
        'smallint',
        'mediumint',
        'bigint'
    ];
    private $string = [
        'char',
        'varchar',
        'text',
        'tinytext',
        'mediumtext',
        'longtext',
        'json'
    ];
    private $float = [
        'double',
        'float',
        'decimal'
    ];
    private $time = [
        'date',
        'datetime',
        'year',
        'time'
    ];
    private $timestamp = [
        'timestamp'
    ];

    public function __construct()
    {
        $this->connect();
        $this->setDb();
        $this->setCharset();
    }

    /**
     * 连接数据库，获取句柄
     */
    private function connect()
    {
        $this->conn = mysqli_connect("$this->host", "$this->userName", "$this->dbPwd") or die("mysqli connect is error.");
    }

    /**
     * 设置数据库名
     */
    private function setDb()
    {
        mysqli_select_db($this->conn, $this->dababase);
    }

    /**
     * 设置头文件
     */
    private function setHeader()
    {
        header("Content-type: text/html; charset=utf-8");
    }

    /**
     * 设置字符集
     */
    private function setCharset()
    {
        $this->query('SET NAMES utf8');
    }

    /**
     * 查询
     * @param $sql
     * @return bool|mysqli_result
     */
    private function query($sql)
    {
        return mysqli_query($this->conn, $sql);
    }

    /**
     * @param $data
     */
    private function p($data)
    {
        echo '<pre>';
        print_r($data);
        echo '<br>';
    }

    /**
     * @param $sql
     * @return array
     */
    public function queryAll($sql)
    {
        $res = mysqli_query($this->conn, $sql);
        $result = [];
        while ($row = mysqli_fetch_assoc($res)) {
            $result[] = $row;
        }
        return $result;
    }

    /**
     * 筛选表
     */
    private function checkTable()
    {
        $tables = $this->queryAll('show tables');
        $flag = false;
        foreach ($tables as $val) {
            if ($this->table == $val['Tables_in_test']) {
                $flag = true;
            }
        }
        if ($flag == false) {
            echo 'There is no table in this database,please check in the config';
            die;
        }
    }

    /**
     * 获取表的字段信息
     * @return mixed
     */
    private function getColumns()
    {
        $sql = 'SELECT * FROM ';
        $sql .= 'INFORMATION_SCHEMA.COLUMNS ';
        $sql .= 'WHERE ';
        $sql .= "table_name = '{$this->table}' AND table_schema = '{$this->dababase}'";
        $this->columns = $this->queryAll($sql);
    }

    /**
     * 查询此类型
     * @param $type
     * @return string
     */
    private function getType($type)
    {
        switch ($type) {
            case in_array($type, $this->int):
            case in_array($type, $this->timestamp):
                return 'int';
                break;
            case in_array($type, $this->string):
            case in_array($type, $this->time):
                return 'string';
                break;
            case in_array($type, $this->float):
                return 'float';
                break;
            default:
                return 'string';
                break;
        }
    }

    /**
     * 获取注释
     * @return string
     */
    private function getProperty()
    {
        $this->p($this->columns);
        $property = '/**'.PHP_EOL.'* '.PHP_EOL;
        foreach ($this->columns as $key => $value) {
            if (!in_array($value['COLUMN_NAME'], $this->noShowFields)) {
                $property .= '* @property '.$this->getType($value['DATA_TYPE']).'      $'.$value['COLUMN_NAME'].'     '.$value['COLUMN_COMMENT'].PHP_EOL;
            }
        }
        $property .= ' * @package App\Models'.PHP_EOL.'*/'.PHP_EOL;
        return $property;
    }

    private function getHead()
    {
        $head = '<?php'.PHP_EOL;
        $head .= '/**'.PHP_EOL;
        $head .= '* @author liyang <liyang@uniondrug.cn>'.PHP_EOL;
        $head .= '* @date   '.date('Y-m-d').PHP_EOL;
        $head .= '*/'.PHP_EOL;
        $head .= 'namespace App\Models;'.PHP_EOL;
        return $head;
    }

    private function getBottom()
    {
        $bottom = 'class Config extends Model
{
    const STATUS_ON = 1;
    const STATUS_OFF = 0;
    private static $_statusText = [
        self::STATUS_ON => \'已开启\',
        self::STATUS_OFF => \'已关闭\'
    ];
    private static $_unknowsMessage = \'非法状态\';

    public function getSource()
    {
        return "config";
    }

    public function getStatusText()
    {
        return isset(static::$_statusText[$this->status]) ? static::$_statusText[$this->status] : static::$_unknowsMessage;
    }
}';
        return $bottom;
    }

    /**
     * 获取内容
     * @return string
     */
    private function getFileContent()
    {
        $html = $this->getHead();
        $html .= $this->getProperty();
        $html .= $this->getBottom();
        return $html;
    }

    /**
     * @return string
     */
    private function getFileDir()
    {
        return 'temp/'.$this->table.'.php';
    }

    public function build()
    {
        $this->setHeader();
        $this->checkTable();
        $this->getColumns();
        $this->getProperty();
        $html = $this->getFileContent();
        file_put_contents($this->getFileDir(), $html);
    }
}

$obj = new MysqlDocs();
$obj->build();
