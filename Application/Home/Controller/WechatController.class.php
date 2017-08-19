<?php
namespace Home\Controller;
use EasyWeChat\Foundation\Application;
use EasyWeChat\Message\News;
use Think\Controller;

require"./vendor/autoload.php"; // 引入 composer 入口文件
class WechatController extends Controller {
    public function index(){
        /*$options = [//微信配置  放到全局配置中
            'debug'  => true,
            'app_id' => 'wx1e2911c6fed8e3e6',
            'secret' => '47b9d2abcada79d623ca2756552f9bb5',
            'token'  => 'wmzibji',
            // 'aes_key' => null, // 可选
            'log' => [
                'level' => 'debug',
                'file'  => 'www/wwwroot/weixin.wmz/easywechat.log', // XXX: 绝对路径！！！！
            ],
            //...
        ];*/
        $app = new Application(C('wechat'));
        $server = $app->server;
        $server->setMessageHandler(function ($message) {
            switch ($message->MsgType) {
                case 'event':
                    //关注事件
                    switch ($message->Event) {   //事件由: subscribe(订阅)、unsubscribe(取消订阅)、CLICK(菜单点击)等
                        case 'subscribe':
                            return "您好！欢迎关注我!";  //回复普通的文本信息
                        case 'unsubscribe':
                            break;
                        case "CLICK":
                            //自定义菜单的点击事件
                            return $message->EventKey;
                            break;
                    }
                    break;
                case 'text':
                    $content = $message->Content;  //接收的文本内容
                    switch ($message->Content) {
                        //最新活动
                        case $content=='最新活动':
                            $news1 = new News(['title' => "最新活动1", 'description' => '最新活动1', 'url'=> "http://www.baidu.com",'image'=> 'http://weixin.wmz.kim/Public/Home/static/image/6.jpg']);
                            $news2 = new News(['title' => "最新活动2", 'description' => '最新活动2', 'url'=> "http://www.baidu.com",'image'=> 'http://weixin.wmz.kim/Public/Home/static/image/7.jpg']);
                            $news3 = new News(['title' => "最新活动3", 'description' => '最新活动3', 'url'=> "http://www.baidu.com",'image'=> 'http://weixin.wmz.kim/Public/Home/static/image/8.jpg']);
                            $news4 = new News(['title' => "最新活动3", 'description' => '最新活动3', 'url'=> "http://www.baidu.com",'image'=> 'http://weixin.wmz.kim/Public/Home/static/image/9.jpg']);
                            return [$news1,$news2,$news3,$news4];
                        //最新通知
                        case $content=='最新通知':
                            $news1 = new News(['title'=> '最新通知1','description'=>'最新通知1','url'  => 'http://weixin.wmz.kim/index.php','image'=>'http://weixin.wmz.kim/Public/Home/static/image/6.jpg',]);
                            $news2 = new News(['title'=> '最新通知2','description'=>'最新通知2','url'  => 'http://weixin.wmz.kim/index.php','image'=>'http://weixin.wmz.kim/Public/Home/static/image/7.jpg',]);
                            $news3 = new News(['title'=> '最新通知3','description'=>'最新通知3','url'  => 'http://weixin.wmz.kim/index.php','image'=>'http://weixin.wmz.kim/Public/Home/static/image/8.jpg',]);
                            $news4 = new News(['title'=> '最新通知3','description'=>'最新通知3','url'  => 'http://weixin.wmz.kim/index.php','image'=>'http://weixin.wmz.kim/Public/Home/static/image/9.jpg',]);
                            return [$news1,$news2,$news3,$news4];
                        //百度周边
                        default:
                            preg_match("/^(\w)(.*)$/",$content,$matches);
                            switch ($matches[1]){
                                //基于位置的搜索 s+搜索内容
                                case 's':
                                    $query = urlencode($matches[2]);//转义
                                    //查询数据库对应open_id的经纬度坐标
                                    $user_location = M('location')->where(['open_id'=>$message->FromUserName])->find();
                                    $location = $user_location['x'].','.$user_location['y'];//经纬度
                                    $search_url = "http://api.map.baidu.com/place/search?query={$query}&location={$location}&radius=1000&output=xml";//图文消息地址
                                    //解析xml
                                    $simpleXml = simplexml_load_file($search_url); //解析
                                    $news = [];//所有的图文消息
                                    $news_count = 0;
                                    foreach ($simpleXml->results->result as $k=>$v){
                                        $url = html_entity_decode($v->detail_url);//将url中的实体符号转换回来
                                        $lng = (string)$v->location->lng;
                                        $lat = (string)$v->location->lat;
                                        //获取百度静态图片
                                        $image_url = "http://api.map.baidu.com/panorama/v2?ak=mzyIoPg42h4yy9Twcvcy9t0oWlvlTbhx&width=512&height=256&location={$lng},{$lat}&fov=180";
                                        $new = new News(['title'=>(string)$v->name,'description'=>(string)$v->address,'url'=>$url,'image'=>$image_url]);
                                        $news[] = $new;
                                        $news_count++;
                                        if($news_count >= 8){//图文消息最多8条
                                            break;
                                        }
                                    }
                                    return $news;
                                    break;
                                //四川搜索天气 l开头的字符串 l+城市名
                                case 'l':
                                    $weatherXml = simplexml_load_file('http://flash.weather.com.cn/wmaps/xml/sichuan.xml');
                                    $weathers = [];
                                    foreach ($weatherXml as $name=>$value){
                                        $weathers[(string)$value['cityname']] = (string)$matches[2].'今日天气：'.$value['stateDetailed'].',最高温度'.$value['tem1'].'℃'.',最低温度'.$value['tem2'].'℃.';
                                    }
                                    if(isset($weathers[$matches[2]])){
                                        $weather= $weathers[$matches[2]];
                                    }else{
                                        $weather= "请输入正确城市名！";
                                    }
                                    return $weather;
                                default:
                                    return '看不懂你的消息';
                                    break;
                            }
//                            return '看不懂你的消息';
                            break;
                    }
                    break;
                case 'image':
                    return '收到图片消息';
                    break;
                case 'voice':
                    return '收到语音消息';
                    break;
                case 'video':
                    return '收到视频消息';
                    break;
                case 'location':
                    $sql = "insert into location(open_id,x,y,scale,label) VALUES ('{$message->FromUserName}','$message->Location_X','$message->Location_Y','{$message->Scale}','{$message->Label}') ON  DUPLICATE KEY UPDATE x='{$message->Location_X}',y='{$message->Location_Y}',scale='{$message->Scale}',label='{$message->Label}'";
                    M()->execute($sql);
                    return '收到坐标消息';
                    break;
                case 'link':
                    return '收到链接消息';
                    break;
                // ... 其它消息
                default:
                    return '收到其它消息';
                    break;
            }
        });
        $response = $server->serve();
        // 将响应输出
        $response->send(); // Laravel 里请使用：return $response;
    }

    public function menu(){
        $app = new Application(C('wechat'));
        $menu = $app->menu;
        $buttons = [
            [
                "name"       => "导航",
                "sub_button" => [
                    [
                        "type" => "view",
                        "name" => "首页",
                        "url"  => "http://weixin.wmz.kim/index.php"
                    ],
                    [
                        "type" => "view",
                        "name" => "业主认证",
                        "url"  => "http://weixin.wmz.kim/index.php"
                    ],
                ],
            ],
            [
                "name"       => "菜单",
                "sub_button" => [
                    [
                        "type" => "view",
                        "name" => "小区通知",
                        "url"  => "http://weixin.wmz.kim/index.php?s=/Home/Notice/index.html"
                    ],
                    [
                        "type" => "view",
                        "name" => "便民服务",
                        "url"  => "http://weixin.wmz.kim/index.php"
                    ],
                    [
                        "type" => "view",
                        "name" => "在线报修",
                        "url" => "http://weixin.wmz.kim/index.php?s=/Home/Repair/online.html"
                    ],
                    [
                        "type" => "view",
                        "name" => "商家活动",
                        "url" => "http://weixin.wmz.kim/index.php"
                    ],
                    [
                        "type" => "view",
                        "name" => "小区租售",
                        "url" => "http://weixin.wmz.kim/index.php"
                    ],
                ],
            ],
            [
                "type" => "view",
                "name" => "个人中心",
                "url"  => "http://weixin.wmz.kim/index.php?s=/Home/User/index.html"
            ],
        ];

        $menu->add($buttons);
    }
    //授权
    public static function getAccess(){

        // 未登录
        if (!SESSION('wechat_user')) {
            $app = new Application(C('wechat'));
            $response = $app->oauth->scopes(['snsapi_userinfo'])
                ->redirect();
            SESSION('target_url',$_SERVER['PATH_INFO']);

            $response->send();
        }
    }
    //回调页面
    public  function callback(){
        $app = new Application(C('wechat'));
        // 获取 OAuth 授权结果用户信息
        $user = $app->oauth->user()->toArray();
        SESSION('wechat_user', $user);
//        var_dump($user["name"]);exit;
        SESSION('open_id',$user["id"]);
        SESSION('user_ip',$_SERVER['REMOTE_ADDR']);
//        var_dump(session('user_ip'));exit;
//        $targetUrl = empty(SESSION('target_url') ? '/' : SESSION('target_url');
        $this->redirect(SESSION('target_url')); // 跳转到
    }
}