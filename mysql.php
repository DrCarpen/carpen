<?php
/**
 * 生成mysqli数据字典
 */

class MysqlDocs
{
    /**
     * *********  命令行用法  **********
     * ******* php mysql.php wx_members ********
     * *******  最后一个参数是目标表名    **
     */
    /**
     * **********   用户自定义配置    ***********
     * **********   不要用我的邮箱哦  ***********
     * **********   出bug了我不背哦   ***********
     */
    private $author = 'liyang';                // 作者名
    private $email = 'liyang@uniondrug.com';    // 作者邮箱
    /**
     * **********   表名必须是以下划线连接 ********
     * **********   如wx_user_name      ********
     * **********   不然我不解析          ********
     * **********   你就老老实实自己写文件去 ******
     */
    private $table = 'users';  // 需生成的表名
    /**
     * **********    数据库基础配置     **********
     */
    private $host = 'localhost';    // 数据库连接
    private $userName = 'root';     // 数据库账号
    private $dbPwd = 'root';        // 数据库密码
    private $database = 'test';     // 数据库名
    /**
     * *********     功能配置        ***********
     */
    private $conn;
    private $noShowFields = [];     //不需要显示的字段
    private $columns = [];
    private $className = '';        // 当前生成的类名
    private $isHasStatus = false;   // 当前是否有status字段
    // int类型包含的子类型
    private $int = [
        'int',
        'integer',
        'tinyint',
        'smallint',
        'mediumint',
        'bigint'
    ];
    // string字符串类型包含的子类型
    private $string = [
        'char',
        'varchar',
        'text',
        'tinytext',
        'mediumtext',
        'longtext',
        'json'
    ];
    // float类型包含的子类型
    private $float = [
        'double',
        'float',
        'decimal'
    ];
    // 日期类型包含的子类型
    private $time = [
        'date',
        'datetime',
        'year',
        'time'
    ];
    // 时间戳类型
    private $timestamp = [
        'timestamp'
    ];

    public function __construct()
    {
        // 初始化入参
        $this->getArgvs();
        // 初始化数据库连接
        $this->connect();
        // 设置数据名
        $this->setDb();
        // 设置字符集
        $this->setCharset();
    }

    /**
     * 处理入参
     */
    private function getArgvs()
    {
        $argvs = $_SERVER['argv'];
        if (count($argvs) > 1) {
            $this->table = $argvs[1];
        }
    }

    /**
     * 核心功能：生成文档
     */
    public function build()
    {
        $this->className = $this->getClassName();
        $this->setHeader();
        $this->checkTable();
        $this->getColumns();
        $this->getProperty();
        $html = $this->getFileContent();
        $this->buildFile($this->getFileDir(), $html);
    }

    /**
     * 连接数据库，获取句柄
     */
    private function connect()
    {
        $this->conn = mysqli_connect("$this->host", "$this->userName", "$this->dbPwd");
    }

    /**
     * 设置数据库名
     */
    private function setDb()
    {
        mysqli_select_db($this->conn, $this->database);
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
     * 断点测试
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
        $sql .= "table_name = '{$this->table}' AND table_schema = '{$this->database}'";
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
        $property = '/**'.PHP_EOL;
        foreach ($this->columns as $key => $value) {
            if (!in_array($value['COLUMN_NAME'], $this->noShowFields)) {
                $property .= '* @property '.$this->getType($value['DATA_TYPE']).'      $'.$value['COLUMN_NAME'].'     '.$value['COLUMN_COMMENT'].PHP_EOL;
            }
            if ($value['COLUMN_NAME'] == 'status') {
                $this->isHasStatus = true;
            }
        }
        $property .= '* @package App\Models'.PHP_EOL.'*/'.PHP_EOL;
        return $property;
    }

    /**
     * 配置头文件
     * @return string
     */
    private function getHead()
    {
        $head = '<?php'.PHP_EOL;
        $head .= '/**'.PHP_EOL;
        $head .= '* @author '.$this->author.' <'.$this->email.'>'.PHP_EOL;
        $head .= '* @date   '.date('Y-m-d').PHP_EOL;
        $head .= '*/'.PHP_EOL;
        $head .= 'namespace App\Models;'.PHP_EOL.PHP_EOL;
        $head .= 'use App\Models\Abstracts\Model;'.PHP_EOL.PHP_EOL;
        return $head;
    }

    /**
     * 配置底部文件
     * @return string
     */
    private function getBottom()
    {
        $bottom = 'class '.$this->className.' extends Model
{
    public function getSource()
    {
        return "'.$this->table.'";
    }';
        if ($this->isHasStatus) {
            $bottom .= '
    const STATUS_ON = 1;
    const STATUS_OFF = 0;
    private static $_statusText = [
        self::STATUS_ON => \'已开启\',
        self::STATUS_OFF => \'已关闭\'
    ];
    private static $_unknowsMessage = \'非法状态\';

   

    public function getStatusText()
    {
        return isset(static::$_statusText[$this->status]) ? static::$_statusText[$this->status] : static::$_unknowsMessage;
    }
';
        }
        $bottom .= PHP_EOL.'}';
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
     * 配置目录
     * @return string
     */
    private function getFileDir()
    {
        return 'temp/'.$this->className.'.php';
    }

    private function buildFile($dir, $html)
    {
        file_put_contents($dir, $html);
    }

    /**
     * 获取类名
     * @return string
     */
    public function getClassName()
    {
        $name = strtolower($this->table);
        $nameArr = explode('_', $name);
        $className = '';
        foreach ($nameArr as $value) {
            $className .= ucfirst($value);
        }
        return $className;
    }
}

$obj = new MysqlDocs();
$obj->build();
//$obj->getClassName();