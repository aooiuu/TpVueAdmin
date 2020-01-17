<?php

namespace app\common\validate;

class Admin extends Base
{
    /**
     * 验证规则
     */
    protected $rule = [
        'username' => 'require|regex:\w{3,12}|unique:admin',
        'password' => 'require|regex:\S{32}',
        'nickname' => 'require',
        'email' => 'require|email|unique:admin,email',
    ];

    /**
     * 验证场景
     */
    protected $scene = [
        'add' => ['username' => 'require', 'nickname' => 'require', 'password' => 'require'],
    ];

    /**
     * 提示消息
     */
    protected $message = [
        'username' => '用户名格式有误',
    ];

    /**
     * 字段描述
     */
    protected $field = [
        'username' => '用户名',
        'nickname' => '昵称',
        'password' => '密码',
    ];
}
