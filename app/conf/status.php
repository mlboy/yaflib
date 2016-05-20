<?php
/*
*+---------------------------------------+
*|                                       |
*|           By mlboy@126.com            |
*|       Copyright (C) 2015  mlboy       |
*|          All rights reserved          |
*|            www.maliang.xyz            |
*|                                       |
*+---------------------------------------+
*/
return array(
    'ok'=>array(
        'status'  => 1,
        'message' => '操作成功',
    ),
    'unknown'=>array(
        'status'  => 0,
        'message' => '操作失败',
    ),
    'signature_error' => array(
        'status' => 100,
        'message' => '签名验证失败',
    ),
    'appid_error' => array(
        'status' =>101,
        'message' =>'appid错误',
    ),
    'params_error' => array(
        'status' =>200,
        'message' =>'参数错误错误',
    ),'params_format_error' => array(
        'status' =>201,
        'message' =>'参数格式错误',
    ),
    'user_not_login' => array(
        'status' => 2000,
        'message' => '用户未登录！',
    ),
    'token_not_valid' =>array(
        'status'   => 2001,
        'message'   => 'token不正确',
    ),
    'vcode_error' =>array(
        'status'   => 2002,
        'message'   => '验证码错误',
    ),
    'user_pw_error' =>array(
        'status'   => 2003,
        'message'   => '用户密码错误',
    ),
    'data_empty' => array(
        'status'  => 1000,
        'message' => '暂无数据',
    ),
    'user_not_found' => array(
        'status' => 1001,
        'message'=> '用户名或密码错误',
    ),
    'booking_is_empty' => array(
        'status' => 1002,
        'message' => '订单不存在或已经过期',
    ),
    'guide_has_booked' => array(
        'status' => 1003,
        'message' =>'很抱歉，您选的助手已经被别人预定或预定当天该助手休息',
    ),
    'update_error' => array(
        'status' => 1004,
        'message' => '数据更新错误',
    ),
    'booking_operate_error' => array(
        'status' => 1005,
        'message' => '订单操作不匹配',
    ),

    'bill_not_found' => array(
        'status' =>1006,
        'message' => '暂无账单',
    ),

    'coupon_not_found' => array(
        'status' =>1007,
        'message' => '优惠券未找到',
    ),
   'coupon_type_too_many' => array(
        'status' =>1007,
        'message' => '优惠券使用种类不可以超过2种',
    ),
    'coupon_num_too_many' => array(
        'status' =>1008,
        'message' => '优惠券使用数量不可以超过2张',
    ),
    'coupon_not_use' => array(
        'status' =>1009,
        'message' => '您选择的优惠券部分不能被使用',
    ),
    'old_password_error' => array(
        'status' =>1010,
        'message' =>'旧密码输入错误！',
    ),
    'password_same_as_old' => array(
        'status' =>1010,
        'message' =>'新密码不可以与旧密码一致！',
    ),
    'coupon_has_rob' => array(
        'status' =>1011,
        'message' => '您已经拥有此类优惠劵',
    ),
    'booking_has_judge' => array(
        'status' =>1012,
        'message' => '您已经评价过此订单',
    ),
    'booking_not_operate' => array(
        'status' => 1013,
        'message' => '只有当天订单才可以操作哦',
    ),
    'repeat_error' =>array(
        'status' =>1013,
        'message' =>'重复操作！',
    ),
    'version_has_new' =>array(
        'status' =>1100,
        'message' =>'当前版本已经是最新！',
    ),
    'wechat_unsafe_callback' =>array(
        'status' => 3000,
        'message' => '未知的微信回调',
    ),
    'wechat_error' => array(
        'status' => 3001,
        'message' =>'微信API: '
    ),
    'wechat_user_refuse' => array(
        'status' => 3002,
        'message' => '微信用户拒绝授权',
    ),
);
