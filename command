#!/usr/bin/env php
<?php
namespace Command;

//require 'App/Commands/MysqlCommand.php';
require 'App/Commands/test.php';
die;
use Dcore\Command\Command;

echo __detectRoot();
require __DIR__.'/'.'Dcore/Command/Command.php';
$command = new Command();
$command->init();
function __detectRoot()
{
    echo $_SERVER['REQUEST_TIME'];
    die;
    $cwd = getcwd();
    $script = $_SERVER['SCRIPT_FILENAME'];
    if (substr($script, 0, 1) != DIRECTORY_SEPARATOR) {
        $script = $cwd.DIRECTORY_SEPARATOR.$script;
    }
    $rootPath = dirname($script);
    while (!file_exists($rootPath.DIRECTORY_SEPARATOR.'app')) {
        $rootPath = dirname($rootPath);
        if ($rootPath == DIRECTORY_SEPARATOR) {
            echo PHP_EOL;
            echo "Error: Cannot detect app root".PHP_EOL;
            echo PHP_EOL;
            exit;
        }
    }
    return $rootPath;
}