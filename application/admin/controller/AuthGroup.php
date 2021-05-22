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
    //当前登录管理员所有子组别
    protected $childrenGroupIds = [];

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('AuthGroup');
        // 取出当前管理员所拥有权限的分组
        $this->childrenGroupIds = $this->auth->getChildrenGroupIds($this->auth->id, true);
    }

    public function index()
    {
        $result = [
            'total' => $this->model->where('id', 'in', $this->childrenGroupIds)->count(),
            'rows' => $this->model->where('id', 'in', $this->childrenGroupIds)->select(),
        ];
        return $this->result($result, 0);
    }

    public function add()
    {
        $params = $this->request->param();
        if (!in_array($params['pid'], $this->childrenGroupIds)) {
            $this->result([], 2, '没有权限添加此节点');
        }

        // 当前登录账号拥有的节点数组
        $currentrules = $this->auth->getRuleIds();
        // 父节点数组
        $parentmodel = model("AuthGroup")->get(['id' => $params['pid']]);
        $parentrules = explode(',', $parentmodel->rules);

        // 过滤父节点没有的权限
        $params['rules'] = in_array('*', $parentrules) ? $params['rules'] : array_intersect($parentrules, $params['rules']);
        // 过滤当前节点没有的权限
        $params['rules'] = in_array('*', $currentrules) ? $params['rules'] : array_intersect($currentrules, $params['rules']);

        $params['rules'] = implode(',', $params['rules']);
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
        $id = $this->request->param('id');
        if (!in_array($id, $this->childrenGroupIds)) {
            $this->result([], 2, '没有权限修改此节点');
        }

        $rules = $this->request->param('rules/a');
        $name = $this->request->param('name');
        $row = $this->model->get(['id' => $id]);
        if (!$row) {
            $this->result([], 2, '角色组不存在');
        }

        // 当前登录账号拥有的节点数组
        $currentrules = $this->auth->getRuleIds();
        // 父节点数组
        $parentmodel = model("AuthGroup")->get(['id' => $row->pid]);
        $parentrules = explode(',', $parentmodel->rules);

        // 过滤父节点没有的权限
        $rules = in_array('*', $parentrules) ? $rules : array_intersect($parentrules, $rules);
        // 过滤当前节点没有的权限
        $rules = in_array('*', $currentrules) ? $rules : array_intersect($currentrules, $rules);

        $params = [];
        $params['rules'] = implode(',', $rules);
        if ($name) {
            $params['name'] = $name;
        }
        $result = $row->save($params);
        if ($result === false) {
            return $this->result($result, 2, $this->model->getError());
        }
        return $this->result($params, 0, '修改成功');
    }

    /**
     * 删除角色组
     */
    public function del()
    {
        $ids = explode(',', $this->request->param('id'));
        $grouplist = $this->auth->getGroups();
        $group_ids = array_map(function ($group) {
            return $group['id'];
        }, $grouplist);
        // 移除掉当前登录账号所在组别
        $ids = array_diff($ids, $group_ids);


        // 循环判断每一个组别是否可删除
        $grouplist = $this->model->where('id', 'in', $ids)->select();
        $groupaccessmodel = model('AuthGroupAccess');

        foreach ($grouplist as $k => $v) {
            $groupone = $groupaccessmodel->get(['group_id' => $v['id']]);
            if ($groupone) {
                // 当前组别下有管理员
                $ids = array_diff($ids, [$v['id']]);
                continue;
            }
            // 当前组别下有子组别
            $groupone = $this->model->get(['pid' => $v['id']]);
            if ($groupone) {
                $ids = array_diff($ids, [$v['id']]);
                continue;
            }
        }
        if (!$ids) {
            return $this->result([], 2, '请先删除当前组别下的管理员和分组');
        }

        $count = $this->model->where('id', 'in', $ids)->delete();
        if ($count) {
            return $this->result([], 0, '删除成功');
        }
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
