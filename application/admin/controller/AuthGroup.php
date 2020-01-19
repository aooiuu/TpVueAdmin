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
        // 已选菜单
        $authGroup = $this->model->get($id);
        $resultRules = []; // 可选菜单
        $parentAuthGroup = [];
        if ($authGroup->pid) {
            $parentAuthGroup = $this->model->get($authGroup->pid);
        } else {
            $parentAuthGroup = $authGroup;
        }
        // --
        $rulesArr = explode(',', $parentAuthGroup->rules);
        if (in_array('*', $rulesArr)) {
            // 返回全部菜单
            $resultRules = $AuthRule;
        } else {
            foreach ($AuthRule as $k => $v) {
                if (in_array($v['id'], $rulesArr)) {
                    $resultRules[] = $v;
                }
            }
        }
        // 兼容
        foreach ($resultRules as $k => $v) {
            $resultRules[$k]['state'] = [
                'selected' => false,
            ];
        }
        // 已选菜单
        $rulesArr = explode(',', $authGroup->rules);
        if (in_array('*', $rulesArr)) {
            foreach ($resultRules as $k => $v) {
                $resultRules[$k]['state'] = [
                    'selected' => true,
                ];
            }
        } else {
            foreach ($resultRules as $k => $v) {
                if (in_array($v['id'], $rulesArr)) {
                    $resultRules[$k]['state'] = [
                        'selected' => true,
                    ];
                }
            }
        }
        $this->result($resultRules, 0, 'success');
    }
}
