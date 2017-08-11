<?php

namespace Admin\Controller;

/**
 * 后台报修订单控制器
 */

class PropertyRepairController extends AdminController {

    /**
     * 报修订单列表
     */
    public function index(){
        /* 获取报修订单列表 */
        $list =  $this->lists('PropertyRepair');
        $this->assign('list', $list);
        $this->meta_title = '报修管理';
        $this->display();
    }

    /**
     * 添加报修订单
     */
    public function add(){
        if(IS_POST){
            $PropertyRepair = D('PropertyRepair');
            $data = $PropertyRepair->create();
            if($data){
                $id = $PropertyRepair->add();
                if($id){
                    $this->success('新增成功', U('index'));
                    //记录行为
                    action_log('update_PropertyRepair', 'PropertyRepair', $id, UID);
                } else {
                    $this->error('新增失败');
                }
            } else {
                $this->error($PropertyRepair->getError());
            }
        } else {
            $this->assign('info',null);
            $this->meta_title = '新增';
            $this->display();
        }
    }

    /**
     * 编辑报修订单
     */
    public function edit($id=0){
        if(IS_POST){
            $PropertyRepair = D('PropertyRepair');
            $data = $PropertyRepair->create();
            if($data){
                if($PropertyRepair->save()){
                    //记录行为
                    action_log('update_PropertyRepair', 'PropertyRepair', $data['id'], UID);
                    $this->success('编辑成功', U('index'));
                } else {
                    $this->error('编辑失败');
                }
            } else {
                $this->error($PropertyRepair->getError());
            }
        } else {
            $info = array();
            /* 获取数据 */
            $info = M('PropertyRepair')->find($id);
            if(false === $info){
                $this->error('获取报修信息错误');
            }
            $this->assign('info', $info);
            $this->meta_title = '编辑报修';
            $this->display('add');
        }
    }

    /**
     * 删除报修订单
     */
    public function del(){
        $id = array_unique((array)I('id',0));

        if ( empty($id) ) {
            $this->error('请选择要操作的数据!');
        }
        $map = array('id' => array('in', $id) );
        if(M('PropertyRepair')->where($map)->delete()){
            //记录行为
            action_log('update_PropertyRepair', 'PropertyRepair', $id, UID);
            $this->success('删除成功');
        } else {
            $this->error('删除失败！');
        }
    }

}