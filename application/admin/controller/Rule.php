<?php

namespace app\admin\controller;

class Rule extends Base
{
    /**
     * @var \app\common\model\AuthRule
     */
    protected $model = null;
    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('AuthRule');
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
}
