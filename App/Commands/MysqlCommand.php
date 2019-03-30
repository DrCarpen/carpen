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
    private $noShowTables = []; //不需要显示的表
    private $noShowFields = []; //不需要显示的字段

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
        $this->conn = @mysqli_connect("$this->host", "$this->userName", "$this->dbPwd") or die("mysqli connect is error.");
    }

    /**
     * 设置数据库名
     */
    private function setDb()
    {
        @mysqli_select_db($this->conn, $this->userName);
    }

    /**
     * 设置字符集
     */
    private function setCharset()
    {
        $this->query('SET NAMES utf8');
    }

    /**
     * 筛选表
     */
    private function getTable()
    {
        //        $tables = $this->queryArray('show tables');
        return [0 => $this->table];
    }

    private function getAllTables()
    {
        //        while ($row = mysqli_fetch_array($table_result)) {
        //            if (!in_array($row[0], $no_show_table)) {
        //                $tables[]['TABLE_NAME'] = $row[0];
        //            }
        //        }
    }

    private function query($sql)
    {
        return mysqli_query($this->conn, $sql);
    }

    private function queryArray($sql)
    {
        $res = $this->query($sql);
        return mysqli_fetch_array($res);
    }

    private function setHeader()
    {
        header("Content-type: text/html; charset=utf-8");
    }

    private function __destruct()
    {
        mysqli_close($this->conn);
    }

    public function getStrut()
    {
        $this->setHeader();
        $tabless = $this->getTable();
        //        print_r($tables);die;
        foreach ($tabless as $k => $v) {
            $sql = 'SELECT * FROM ';
            $sql .= 'INFORMATION_SCHEMA.COLUMNS ';
            $sql .= 'WHERE ';
            $sql .= "table_name = '{$v}' ";
            $fields = $this->queryArray($sql);
        }
        echo '<pre>';
        print_r($fields);
        die;
    }
}

$obj = new MysqlDocs();
$obj->getStrut();
die;
//循环取得所有表的备注及表中列消息
if (@$_GET[id] != '') {
    $file = iconv("utf-8", "GBK", "test");
    header("Content-Type: application/doc");
    header("Content-Disposition: attachment; filename=".$file.".doc");
    echo $html;
}
echo '<pre>';
print_r($tables);
die;
$html = '';
//循环所有表
foreach ($tables as $k => $v) {
    $html .= '    <h3>'.($k + 1).'、'.$v['TABLE_COMMENT'].'  （'.$v['TABLE_NAME'].'）</h3>'."\n";
    $html .= '    <table border="1" cellspacing="0" cellpadding="0" width="100%">'."\n";
    $html .= '        <tbody>'."\n";
    $html .= '            <tr>'."\n";
    $html .= '                <th>字段名</th>'."\n";
    $html .= '                <th>数据类型</th>'."\n";
    $html .= '                <th>默认值</th>'."\n";
    $html .= '                <th>允许非空</th>'."\n";
    $html .= '                <th>主外键</th>'."\n";
    $html .= '                <th>自动递增</th>'."\n";
    $html .= '                <th>备注</th>'."\n";
    $html .= '            </tr>'."\n";
    foreach ($v['COLUMN'] as $f) {
        if (@!is_array($no_show_field[$v['TABLE_NAME']])) {
            $no_show_field[$v['TABLE_NAME']] = [];
        }
        if (!in_array($f['COLUMN_NAME'], $no_show_field[$v['TABLE_NAME']])) {
            $html .= '            <tr>'."\n";
            $html .= '                <td class="c1">'.$f['COLUMN_NAME'].'</td>'."\n";
            $html .= '                <td class="c2">'.$f['COLUMN_TYPE'].'</td>'."\n";
            $html .= '                <td class="c3">'.$f['COLUMN_DEFAULT'].'</td>'."\n";
            $html .= '                <td class="c4">'.$f['IS_NULLABLE'].'</td>'."\n";
            $html .= '                <td class="c5">'.$f['COLUMN_KEY'].'</td>'."\n";
            $html .= '                <td class="c6">'.($f['EXTRA'] == 'auto_increment' ? '是' : '&nbsp;').'</td>'."\n";
            $html .= '                <td class="c7">'.$f['COLUMN_COMMENT'].'</td>'."\n";
            $html .= '            </tr>'."\n";
        }
    }
    $html .= '        </tbody>'."\n";
    $html .= '    </table>'."\n";
}
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>数据库表结构</title>
    <meta name="generator" content="ThinkDb V1.0"/>
    <meta name="author" content=""/>
    <meta name="copyright" content="2008-2014 Tensent Inc."/>
    <style>
        body, td, th {
            font-family: "微软雅黑";
            font-size: 14px;
        }

        .warp {
            margin: auto;
            width: 900px;
        }

        .warp h3 {
            margin: 0px;
            padding: 0px;
            line-height: 30px;
            margin-top: 10px;
        }

        table {
            border-collapse: collapse;
            border: 1px solid #CCC;
            background: #efefef;
        }

        table th {
            text-align: left;
            font-weight: bold;
            height: 26px;
            line-height: 26px;
            font-size: 14px;
            text-align: center;
            border: 1px solid #CCC;
            padding: 5px;
        }

        table td {
            height: 20px;
            font-size: 14px;
            border: 1px solid #CCC;
            background-color: #fff;
            padding: 5px;
        }

        .c1 {
            width: 120px;
        }

        .c2 {
            width: 120px;
        }

        .c3 {
            width: 150px;
        }

        .c4 {
            width: 80px;
            text-align: center;
        }

        .c5 {
            width: 80px;
            text-align: center;
        }

        .c6 {
            width: 80px;
        }

        .c7 {
            width: 190px;
        }
    </style>
</head>
<body>
<div class="warp">
    <h1 style="text-align:center;">数据库表结构</h1>
    <a href="#"><p onclick="window.location.href='test.php?id=2'">点击跳到下载页面</p></a>
    <?php
    echo $html
    //防止导出乱码
    ?>
</div>
</body>
</html>