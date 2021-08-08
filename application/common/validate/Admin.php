<?php

namespace app\common\validate;

class Admin extends Base
{
    /**
     * 验证规则
     */
    protected $rule = [
        'username' => 'require|regex:\w{3,12}',
        'password' => 'require|regex:\S{32}',
        'nickname' => 'require',
    ];

    /**
     * 验证场景
     */
    protected $scene = [
        'add' => [
            'username' => 'require|regex:\w{3,12}|unique:admin',
            'password' => 'require|regex:\S{32}',
            'nickname' => 'require',
        ],
        'edit' => [],
    ];

    /**
     * 提示消息
     */
    protected $message = [
        'username.unique' => '用户名已存在',
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
