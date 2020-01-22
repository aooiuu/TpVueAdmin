<?php

namespace app\common\model;

class AuthGroupAccess extends Base
{

    // 关联角色组, 一对1
    public function authGroup()
    {
        return $this->hasOne('AuthGroup');
    }
}
