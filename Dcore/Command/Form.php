<?php
/**
 * @author liyang <liyang@uniondrug.cn>
 * @date   2019-03-25
 */
namespace Dcore\Command;

class FormatOutput
{
    /**
     * none         = "\033[0m"
     * black        = "\033[0;30m"
     * dark_gray    = "\033[1;30m"
     * blue         = "\033[0;34m"
     * light_blue   = "\033[1;34m"
     * green        = "\033[0;32m"
     * light_green -= "\033[1;32m"
     * cyan         = "\033[0;36m"
     * light_cyan   = "\033[1;36m"
     * red          = "\033[0;31m"
     * light_red    = "\033[1;31m"
     * purple       = "\033[0;35m"
     * light_purple = "\033[1;35m"
     * brown        = "\033[0;33m"
     * yellow       = "\033[1;33m"
     * light_gray   = "\033[0;37m"
     * white        = "\033[1;37m"
     */
    private static function getYellowString($string)
    {
        return "\e[1;33m ".$string."\e[0m ";
    }

    /**
     * \033[0m  关闭所有属性
     * \033[1m   设置高亮度
     * \03[4m   下划线
     * \033[5m   闪烁
     * \033[7m   反显
     * \033[8m   消隐
     * \033[30m   --   \033[37m   设置前景色
     * \033[40m   --   \033[47m   设置背景色
     */
    private static function getFormString($string)
    {
        return $string;
    }

    /**
     * \r\n 换行
     * \n 换行 linux
     * @param $string
     * @return mixed
     */
    private static function getWrapString($string)
    {
        return $string;
    }

    /**
     * \t到底跳过几个空格是没有规定，也没有标准的,每个输出设备会规定自己设备上\t会定位到某个整数单位倍数处，
     * 比如有的设备规定\t定位到8字符整数倍数处，
     * 假定某个字符串在制表符\t的前面有n个字符，那么\t将跳过8-n%8个空格。
     * @param $string
     * @return mixed
     */
    private static function getTabString($string)
    {
        return $string;
    }
}