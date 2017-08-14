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
                    switch ($message->Event) {   //事件由: subscribe(订阅)、unsubscribe(取消订阅)、CLICK(菜单点击)等
                        case 'subscribe':
                            return "您好！欢迎关注我!";  //回复普通的文本信息
                        case 'unsubscribe':
                            break;
                    }
                    break;
                case 'text':
                    $content = $message->Content;  //接收的文本内容
                    switch ($message->Content) {
                        case $content=='最新活动':
                            $news1 = new News(['title' => "最新活动1", 'description' => '最新活动1', 'url'=> "http://www.baidu.com",'image'=> 'http://weixin.wmz.kim/Public/Home/static/image/6.jpg']);
                            $news2 = new News(['title' => "最新活动2", 'description' => '最新活动2', 'url'=> "http://www.baidu.com",'image'=> 'http://weixin.wmz.kim/Public/Home/static/image/7.jpg']);
                            $news3 = new News(['title' => "最新活动3", 'description' => '最新活动3', 'url'=> "http://www.baidu.com",'image'=> 'http://weixin.wmz.kim/Public/Home/static/image/8.jpg']);
                            $news4 = new News(['title' => "最新活动3", 'description' => '最新活动3', 'url'=> "http://www.baidu.com",'image'=> 'http://weixin.wmz.kim/Public/Home/static/image/9.jpg']);
                            return [$news1,$news2,$news3,$news4];
                        case $content=='最新通知':
                            $news1 = new News(['title'=> '最新通知1','description'=>'最新通知1','url'  => 'http://weixin.wmz.kim/index.php','image'=>'http://weixin.wmz.kim/Public/Home/static/image/6.jpg',]);
                            $news2 = new News(['title'=> '最新通知2','description'=>'最新通知2','url'  => 'http://weixin.wmz.kim/index.php','image'=>'http://weixin.wmz.kim/Public/Home/static/image/7.jpg',]);
                            $news3 = new News(['title'=> '最新通知3','description'=>'最新通知3','url'  => 'http://weixin.wmz.kim/index.php','image'=>'http://weixin.wmz.kim/Public/Home/static/image/8.jpg',]);
                            $news4 = new News(['title'=> '最新通知3','description'=>'最新通知3','url'  => 'http://weixin.wmz.kim/index.php','image'=>'http://weixin.wmz.kim/Public/Home/static/image/9.jpg',]);
                            return [$news1,$news2,$news3,$news4];
                        case $content=='tq'.'[\u4e00-\u9fa5]'://查询天气 l.[\u4e00-\u9fa5]
                            $cc=substr((string)$content,2);
                            $weatherXml = simplexml_load_file('http://flash.weather.com.cn/wmaps/xml/sichuan.xml');
                            $weathers = [];
                            foreach ($weatherXml as $name=>$value){
                                $weathers[(string)$value['cityname']] = (string)$value['stateDetailed'].'    最高温度：'.$value['tem1'].'最低温度:'.$value['tem2'];
                            }
                            if(isset($weathers[$cc])){
                                $weather= $weathers[$cc];
                            }else{
                                $weather= "请输入正确的四川城市名！";
                            }
                            return $weather;
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
        if (!SESSION('open_id')) {
            $app = new Application(C('wechat'));
            $response = $app->oauth->scopes(['snsapi_base'])
                ->redirect();
            SESSION('target_url',$_SERVER['PATH_INFO']);
            $response->send();
        }
    }
    //回调页面
    public  function callback(){
        $app = new Application(C('wechat'));
        // 获取 OAuth 授权结果用户信息
        $user = $app->oauth->user();
        SESSION('open_id',$user->getId());
//        $targetUrl = empty($_SESSION['target_url']) ? '/' : $_SESSION['target_url'];
        $this->redirect(SESSION('target_url')); // 跳转到
    }
}