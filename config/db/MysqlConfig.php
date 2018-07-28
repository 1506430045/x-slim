<?php
/**
 * Mysql配置
 *
 * User: forward
 * Date: 2018/6/25
 * Time: 下午8:50
 */

namespace Config\db;


class MysqlConfig
{
    //线上 root root
    //测试 root   ws_Tb_sas31121F
    //本机 root   123456
    public static $baseConfig = [
        'host' => '127.0.0.1',
        'dbname' => 'candy',
        'username' => 'root',
        'password' => 'root',
        'charset' => 'utf8',
        'options' => [
            \PDO::ATTR_STRINGIFY_FETCHES => false,
            \PDO::ATTR_EMULATE_PREPARES => false,
            \PDO::MYSQL_ATTR_INIT_COMMAND => "set names utf8",
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
        ]
    ];
}