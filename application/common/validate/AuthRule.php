<?php

namespace app\common\validate;

class AuthRule extends Base
{
    /**
     * 正则
     */
    protected $regex = ['format' => '[a-z0-9_\/]+'];

    /**
     * 验证规则
     */
    protected $rule = [
        'name' => 'require|format|unique:AuthRule',
        'title' => 'require',
    ];

    /**
     * 验证场景
     */
    protected $scene = [
    ];

    /**
     * 提示消息
     */
    protected $message = [
        'name.format' => 'URL规则只能是小写字母、数字、下划线和/组成',
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
