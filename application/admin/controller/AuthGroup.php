<?php

namespace app\admin\controller;

use app\common\model\AuthRule;

class AuthGroup extends Base
{
    /**
     * @var \app\common\model\AuthGroup
     */
    protected $model = null;
    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('AuthGroup');
    }

    public function index()
    {
        $result = [
            'total' => $this->model->count(),
            'rows' => $this->model->select(),
        ];
        return $this->result($result, 0);
    }

    public function add()
    {
        $params = $this->request->param();
        $result = $this->model->validate()->save($params);
        if ($result) {
            $this->result([], 0, '操作成功');
        } else {
            $this->result([], 2, '操作失败');
        }
    }

    public function edit()
    {
    }

    public function del()
    {
    }

    /**
     * 读取角色权限树
     */
    public function roletree()
    {
        $id = $this->request->param('id');
        if (!isset($id)) {
            $this->result([], 2, '缺少参数');
        }

        // 取出所有菜单
        $AuthRule = collection(AuthRule::select())->toArray();
        // 取出所有可选菜单

        // 获取已分配的菜单
        $this->result($AuthRule, 0, '操作成功');
    }
}
