<?php

namespace app\common\model;

class Admin extends Base
{
    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';

    // 关联角色组绑定表, 一对多
    public function authGroupAccess()
    {
        return $this->hasMany('AuthGroupAccess', 'uid');
    }

}
