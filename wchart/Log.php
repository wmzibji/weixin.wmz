<?php

/**
 * 日志类
 * Class Log
 */
class Log
{
    /**
     * @param $data 日志内容
     * @param $filename 文件路径
     */
    public static function write($filename,$data){
        $log = date("Y-m-d H:i:s")."\n";
        $log .= $data."\n";
        $log .= "=====================================================\r\n";
        file_put_contents($filename,$log,FILE_APPEND);
    }
}