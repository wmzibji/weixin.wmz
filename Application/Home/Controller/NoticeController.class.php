<?php


namespace Home\Controller;
use Think\Model;
class NoticeController extends HomeController {
    public function index(){
        $list=M('document')->select();
        $this->assign('list',$list);
        $this->meta_title = '小区通知';
        $this->display();

    }

    /**
     * @param $id
     */
    public function detail($id){
        $list=M('document  as  a')->join('document_article  as  b  on b.id = a.id')->where(['a.id'=>$id])->select();
        $this->assign('list', $list);
        $this->display();
    }
}