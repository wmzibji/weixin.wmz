<?php


namespace Home\Controller;

class NoticeController extends HomeController {
    public function index(){
        $list=M('document')->select();
        $this->assign('list',$list);
        $this->meta_title = '小区通知';
        $this->display();

    }

}