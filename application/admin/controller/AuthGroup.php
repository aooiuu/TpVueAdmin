<?php

namespace app\admin\controller;

use app\common\model\AuthGroupAccess;
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

    /**
     * 编辑角色组
     */
    public function edit()
    {
        // 判断当前修改的节点是否有权限修改
        // $this->childrenGroupIds = $this->auth->getChildrenGroupIds(true);

        // 多级权限管理
        // 分配时不能超过最高层级
        // 那就只搞一级

        // id name rules
        $id = $this->request->param('id');
        $rules = $this->request->param('rules/a');
        $name = $this->request->param('name');
        $row = $this->model->get(['id' => $id]);
        if (!$row) {
            $this->result([], 2, '角色组不存在');
        }
        $params = [];
        if ($rules) {
            $params['rules'] = implode(',', $rules);
        }
        if ($name) {
            $params['name'] = $name;
        }
        $result = $row->save($params);
        if ($result === false) {
            return $this->result($result, 2, $this->model->getError());
        }
        return $this->result($params, 0, $result);
    }

    public function del()
    {
    }

    /**
     * 读取角色权限树
     * @param string $id 为空则获取自身的权限树
     */
    public function roletree()
    {
        $id = $this->request->param('id');
        if (!isset($id)) {
            $authGroup = AuthGroupAccess::get(['uid' => $this->auth->id]);
            if ($authGroup) {
                $id = $authGroup->group_id;
            } else {
                $this->result([], 2, '缺少参数');
            }
        }
        // 取出所有菜单
        $AuthRule = collection(AuthRule::select())->toArray();
        // 已选菜单
        $authGroup = $this->model->get($id);
        $resultRules = []; // 可选菜单
        $rulesArr = $this->auth->getRuleIds($this->auth->id);
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
