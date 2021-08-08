<?php

namespace app\admin\controller;

use app\common\model\Admin;
use think\Validate;

class Index extends Base
{

    // 不需要鉴权的方法
    protected $noNeedRight = ['login', 'logout'];

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('admin');
    }

    public function index()
    {
        return 'index';
    }

    public function login()
    {
        $username = $this->request->param('username');
        $password = $this->request->param('password');
        $rule = [
            'username' => 'require|length:3,30',
            'password' => 'require|length:3,30',
        ];
        $data = [
            'username' => $username,
            'password' => $password,
        ];
        $validate = new Validate($rule, [], []);
        $result = $validate->check($data);
        if (!$result) {
            $this->result([], 2, '信息有误');
        }
        $admin = Admin::get(['username' => $username]);
        if (!$admin) {
            $this->result([], 2, '用户不存在');
        }

        if ($admin->password != md5(md5($password) . $admin->salt)) {
            $this->result([], 2, '密码错误');
        }
        $admin->accesstoken = 'accesstoken';
        $this->auth->login($admin);
        $this->result($this->auth->getUserInfo(), 0, '登录成功');
    }

    public function userinfo()
    {
        $this->result($this->auth->getUserInfo(), 0, 'success');
    }

    public function logout()
    {
        $this->auth->logout();
        $this->result([], 0, '退出登录成功');
    }
}
