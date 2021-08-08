<?php

namespace app\admin\controller;

use think\Validate;

class Admin extends Base
{

    /**
     * @var \app\common\model\Admin
     */
    protected $model = null;
    protected $noNeedRight = [];

    protected $childrenGroupIds = [];
    protected $childrenAdminIds = [];

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('Admin');

        $this->childrenAdminIds = $this->auth->getChildrenAdminIds(true);
        $this->childrenGroupIds = $this->auth->getChildrenGroupIds(true);
    }

    /**
     * 管理员列表
     */
    public function index()
    {
        // 取出所有角色组
        $authGroup = model('AuthGroup')->column('name', 'id');
        // 取出所有关联表
        $authGroupAccess = model('AuthGroupAccess')->select();
        // 给关联表赋值角色组
        foreach ($authGroupAccess as $k => $v) {
            $authGroupAccess[$k]['auth_group'] = [
                'name' => $authGroup[$v['group_id']],
            ];
        }
        // 取出所有用户
        // group_id -> auth_group -> auth_group_access -> admin
        $this->childrenAdminIds = $this->auth->getChildrenAdminIds(true);
        $admin = collection($this->model->where('id', 'in', $this->childrenAdminIds)->select())->toArray();

        // 给用户赋值关联数据
        foreach ($admin as $k => $v) {
            $admin[$k]['auth_group_access'] = [];
            foreach ($authGroupAccess as $kk => $vv) {
                if ($v['id'] == $vv['uid']) {
                    $admin[$k]['auth_group_access'][] = $vv;
                }
            }
        }

        $result = [
            'total' => $this->model->count(),
            'rows' => $admin,
        ];
        return $this->result($result, 0);
    }

    /**
     * 添加管理员
     * @param string username
     * @param string password
     */
    public function add()
    {
        $params = $this->request->param();
        $group = $params['group'];
        unset($params['group']);

        if (!Validate::is($params['password'], '\S{6,16}')) {
            return $this->result([], 2, '请输出长度6-16的密码');
        }

        // 加密密码
        $params['salt'] = randomStr();
        $params['password'] = md5(md5($params['password']) . $params['salt']);

        $result = $this->model->validate('Admin.add')->save($params);
        if ($result === false) {
            return $this->result($result, 2, $this->model->getError());
        }

        // 过滤不允许的组别,避免越权
        $group = array_intersect($this->childrenGroupIds, $group);
        $groupData = [];
        foreach ($group as $value) {
            $groupData[] = [
                'uid' => $this->model->id,
                'group_id' => $value,
            ];
        }
        // 保存分组
        model('AuthGroupAccess')->saveAll($groupData);
        return $this->result($params, 0, '操作成功');
    }

    public function edit()
    {
        $params = $this->request->param();
        $group = $params['group'];
        unset($params['group']);

        $model = $this->model->get(['id' => $params['id']]);
        unset($params['id']);
        if (!$model) {
            return $this->result([], 2, '操作失败,用户不存在');
        }
        if (!in_array($model->id, $this->childrenAdminIds)) {
            return $this->result([], 2, '操作失败,权限不足');
        }

        // 加密密码
        $params['salt'] = randomStr();
        $params['password'] = md5(md5($params['password']) . $params['salt']);

        // 保存用户信息
        $result = $model->validate('Admin.edit')->save($params);
        if ($result === false) {
            return $this->result($result, 2, '操作失败：' . $model->getError());
        }
        // 删除所有权限
        model('AuthGroupAccess')->where('uid', $model->id)->delete();
        // 保存分组
        $groupData = [];
        foreach ($group as $value) {
            $groupData[] = [
                'uid' => $model->id,
                'group_id' => $value,
            ];
        }
        model('AuthGroupAccess')->saveAll($groupData);
        return $this->result([], 0, '操作成功');
    }

    public function del()
    {
        $id = $this->request->param('id');
        // 取出当前管理员所拥有权限的分组
        $childrenGroupIds = $this->childrenGroupIds;
        // 当前管理员的子级管理员
        $adminList = $this->model->where('id', $id)->where('id', 'in', function ($query) use ($childrenGroupIds) {
            // 避免越级删除
            $query->name('auth_group_access')->where('group_id', 'in', $childrenGroupIds)->field('uid');
        })->select();
        if ($adminList) {
            $deleteIds = [];
            foreach ($adminList as $k => $v) {
                $deleteIds[] = $v->id;
            }
            // 排除当前登录的管理员
            $deleteIds = array_values(array_diff($deleteIds, [$this->auth->id]));
            if ($deleteIds) {
                $this->model->destroy($deleteIds);
                // 删除角色组关键表数据
                model('AuthGroupAccess')->where('uid', 'in', $deleteIds)->delete();
                return $this->result([], 0, '操作成功');
            }
        }
        return $this->result([], 2, '操作失败');
    }
}
