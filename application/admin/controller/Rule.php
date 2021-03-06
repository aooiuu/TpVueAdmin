<?php

namespace app\admin\controller;

class Rule extends Base
{
    /**
     * @var \app\common\model\AuthRule
     */
    protected $model = null;
    protected $noNeedRight = ['asyncRoutes'];

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('AuthRule');

        if (!$this->auth->isSuperAdmin()) {
            return $this->result([], 1, '仅超级管理员有此权限');
        }
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
            $this->result([], 2, '操作失败：' . $this->model->getError());
        }
    }

    public function edit()
    {
        $params = $this->request->param();
        if (empty($params['id'])) {
            $this->result([], 2, '操作失败：缺少参数');
        }
        $model = $this->model->get($params['id']);
        unset($params['id']);
        $result = $model->isUpdate()->save($params);
        if ($result || $result === 0) {
            $this->result([], 0, '操作成功');
        } else {
            $this->result([], 2, '操作失败：' . $model->getError());
        }
    }

    public function del()
    {
        $id = $this->request->param('id');
        if (!isset($id)) {
            $this->result([], 2, '缺少参数');
        }
        $this->model->destroy($id);
        $this->result([], 0, '操作成功');
    }

    public function asyncRoutes()
    {
        $ids = $this->auth->getRuleIds();
        if (in_array('*', $ids)) {
            // 超级管理员
            $model = $this->model->where(['ismenu' => 1])->select();
        } else {
            $model = $this->model->where(['ismenu' => 1, 'id' => ['in', $ids]])->select();
        }

        $result = [];
        foreach ($model as $k => $v) {
            if (!$v['ismenu']) {
                continue;
            }
            $v['path'] = $v['name'];
            $v['component'] = $v['name'];
            $v['name'] = camelize(str_replace('/', '_', $v['name']));
            $v['meta'] = [
                'title' => $v['title'],
                'icon' => $v['icon'],
            ];
            $result[] = $v;
        }
        $this->result($result, 0, 'success');
    }
}
