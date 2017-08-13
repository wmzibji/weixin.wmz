<?php
$simple=simplexml_load_file('http://flash.weather.com.cn/wmaps/xml/sichuan.xml');
foreach($simple->city as $weather){
    echo($weather['cityname'].'----'.$weather['stateDetailed']);
}