<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com>
// +----------------------------------------------------------------------

namespace Admin\Model;
use Think\Model;

/**
 * 导航模型
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */

class PropertyRepairModel extends Model {
    protected $_validate = array(
        array('sn', 'require', '报修订单号', self::MUST_VALIDATE , 'regex', self::MODEL_BOTH),
        array('user', 'require', '报修人', self::MUST_VALIDATE , 'regex', self::MODEL_BOTH),
        array('tel', 'require', '电话', self::MUST_VALIDATE , 'regex', self::MODEL_BOTH),
        array('address', 'require', '地址', self::MUST_VALIDATE , 'regex', self::MODEL_BOTH),
        array('problem', 'require', '报修问题', self::MUST_VALIDATE , 'regex', self::MODEL_BOTH),
    );

    protected $_auto = array(
        array('create_time', NOW_TIME, self::MODEL_INSERT),
//        array('update_time', NOW_TIME, self::MODEL_BOTH),
        array('status', '0', self::MODEL_INSERT),
    );

}
