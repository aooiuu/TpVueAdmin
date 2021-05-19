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
        // 取出当前管理员所拥有权限的分组
        $this->childrenGroupIds = $this->auth->getChildrenGroupIds($this->auth->id, true);

        $result = [
            'total' => $this->model->count(),
            'rows' => $this->model->where('id', 'in', $this->childrenGroupIds)->select(),
        ];
        return $this->result($result, 0);
    }

    public function add()
    {
        $params = $this->request->param();
        if ($params['rules']) {
            $params['rules'] = implode(',', $params['rules']);
        }
        $result = $this->model->validate('AuthGroup.add')->save($params);
        if ($result) {
            $this->result([], 0, '操作成功');
        } else {
            $this->result([], 2, '操作失败:' . $this->model->getError());
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
        // 当前组是否有子级
        $id = $this->request->param('id');
        $authGroup = $this->model->get($id);
        $subAuthGroupCount = $this->model->where(['pid' => $authGroup->id])->count();
        if ($subAuthGroupCount != 0) {
            return $this->result([], 0, '当前管理组下存在其他管理组');
        }
        if ($authGroup->delete()) {
            return $this->result([], 0, '删除成功');
        } else {
            return $this->result([], 0, '删除失败：' . $this->model->getError());
        }
        // array_diff() // 其他参数如果全部不包含第一个参数的某成员，就返回这个成员
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
        $AllRules = collection(AuthRule::select())->toArray();
        // 已选菜单
        $authGroup = $this->model->get($id);
        $authGroupIds = explode(',', $authGroup->rules);
        // 可选菜单
        $rulesIdArr = [];
        // 最终返回的
        $resultRules = [];
        if ($authGroup->pid != 0) {
            $pAuthGroup = $this->model->get($authGroup->pid);
            $rulesIdArr = explode(',', $pAuthGroup->rules);
        } else {
            // 超级管理员
            $rulesIdArr = ['*'];
        }
        if (in_array('*', $rulesIdArr)) {
            // 返回全部菜单
            $resultRules = $AllRules;
        } else {
            foreach ($AllRules as $k => $v) {
                if (in_array($v['id'], $rulesIdArr)) {
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
        if (in_array('*', $authGroupIds)) {
            foreach ($resultRules as $k => $v) {
                $resultRules[$k]['state'] = [
                    'selected' => true,
                ];
            }
        } else {
            foreach ($resultRules as $k => $v) {
                if (in_array($v['id'], $authGroupIds)) {
                    $resultRules[$k]['state'] = [
                        'selected' => true,
                    ];
                }
            }
        }
        $this->result($resultRules, 0, 'success');
    }
}
