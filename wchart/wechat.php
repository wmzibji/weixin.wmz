<?php
require "Log.php";
//如果传入了echostr才验证服务器
if($_GET['echostr']){
    //1.验证服务器
    $token = "wmzibji";
    $signature = $_GET['signature'];
    $timestamp = $_GET['timestamp'];
    $nonce = $_GET['nonce'];
    /**
     * 1）将token、timestamp、nonce三个参数进行字典序排序
    2）将三个参数字符串拼接成一个字符串进行sha1加密
    3）开发者获得加密后的字符串可与signature对比，标识该请求来源于微信
     */
    $arr = [$token,$timestamp,$nonce];
    sort($arr);
    $pwd = sha1(implode('',$arr));
    if($pwd == $signature){
        echo $_GET['echostr'];
    }
}else{
//    当普通微信用户向公众账号发消息时，微信服务器将POST消息的XML数据包发送到开发者填写的URL上。
    //1.接受xml数据包 接受post原始数据
        $xml = file_get_contents("php://input");
    //2.保存日志
//        file_put_contents('wchat.log',$xml);
    Log::write('wchat.log',$xml);
    //3.解析获取到xml数据
        $simpleXml = simplexml_load_string($xml);

        $request = [];
        foreach ($simpleXml as $name=>$value){
            $request[$name] = (string)$value;
        }
    //4.回复消息
        //解析天气
        $weatherXml1 = simplexml_load_file('http://flash.weather.com.cn/wmaps/xml/china.xml');
        $weather1 = [];
        foreach ($weatherXml1 as $name=>$value){
            $weather11[(string)$value['quName']] = (string)$value['stateDetailed'].'    最高温度：'.$value['tem1'].'最低温度:'.$value['tem2'];
        }


        $weatherXml = simplexml_load_file('http://flash.weather.com.cn/wmaps/xml/sichuan.xml');
        $weather = [];
        foreach ($weatherXml as $name=>$value){
            $weather[(string)$value['cityname']] = (string)$value['stateDetailed'].'    最高温度：'.$value['tem1'].'最低温度:'.$value['tem2'];
        }
        $conten = '';
        if(isset($weather[$request['Content']])){
            $content .= $weather[$request['Content']];
        }elseif(isset($weather1[$request['Content']])){
            $content .= $weather1[$request['Content']];
        }else{
            $content .= "不知道你在说什么！";
        }

        require "message_text.xml";
}