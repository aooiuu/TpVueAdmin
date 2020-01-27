<?php

namespace app\admin\controller;

class Admin extends Base
{

    /**
     * @var \app\common\model\Admin
     */
    protected $model = null;
    protected $noNeedRight = [];
    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('Admin');
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
        $admin = collection($this->model->select())->toArray();
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
        $result = $this->model->validate('Admin.add')->save($params);
        if ($result === false) {
            return $this->result($result, 2, $this->model->getError());
        }
        // 保存分组
        $groupData = [];
        foreach ($group as $value) {
            $groupData[] = [
                'uid' => $this->model->id,
                'group_id' => $value,
            ];
        }
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
            return $this->result([], 2, '操作失败： 用户不存在');
        }
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
        // 删除所有分组
        $this->model->destroy($id);
        $deleteIds = [$id];
        // TODO: 删除子级的用户
        model('AuthGroupAccess')->where('uid', 'in', $deleteIds)->delete();
        return $this->result([], 0, '操作成功');
    }
}
