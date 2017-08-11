<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Home\Controller;

/**
 * 前台报修控制器
 */
class RepairController extends HomeController {
    public function online(){
        if(IS_POST){
            $Repair = D('Repair');
            $data = $Repair->create();
            if($data){
                $id = $Repair->add();
                if($id){
                    $this->success('报修成功', U('index'));
                    //记录行为
                    action_log('update_Repair', 'Repair', $id, UID);
                } else {
                    $this->error('报修失败');
                }
            } else {
                $this->error($Repair->getError());
            }
        } else {
            $this->assign('info',null);
            $this->meta_title = '报修';
            $this->display();
        }
    }

}