<?php

namespace app\admin\controller;

class Index extends Base
{

    // 不需要鉴权的方法
    protected $noNeedRight = ['logout'];
    public function index()
    {
        return 'index';
    }
    public function login()
    {
        return json_encode($this->request->param());
    }
    public function logout()
    {
        return '1';
    }
}
