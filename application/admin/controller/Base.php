<?php

namespace app\admin\controller;

use app\admin\library\Auth;
use think\Controller;
use think\Loader;

class Base extends Controller
{
    public function _initialize()
    {
        $this->auth = Auth::instance();
        $modulename = $this->request->module();
        $controllername = Loader::parseName($this->request->controller());
        $actionname = strtolower($this->request->action());
        $path = str_replace('.', '/', $controllername) . '/' . $actionname;

        // 设置当前请求的URI
        $this->auth->setRequestUri($path);

        // 判断当前方法是否需要验证权限
        if (!$this->auth->match($this->noNeedRight)) {
            // 验证权限
            // 获取uid
            $uid = 1;
            if (!$this->auth->check($path, $uid)) {
                $this->result([], 202, '用户未登录', 'json');
            }
        }
    }

    protected function result($data, $code = 0, $msg = '', $type = 'json', array $header = [])
    {
        parent::result($data, $code, $msg, $type);
    }
}
