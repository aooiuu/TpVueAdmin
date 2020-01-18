<?php

namespace app\admin\controller;

class Admin extends Base
{

    /**
     * @var \app\common\model\Admin
     */
    protected $model = null;
    protected $noNeedRight = ['add'];
    public function _initialize()
    {
        $this->model = model('Admin');
    }
    /**
     * 添加管理员
     * @param string username
     * @param string password
     */
    public function add()
    {
        $params = $this->request->param();

        $result = $this->model->validate('Admin.add')->save($params);
        if ($result === false) {
            return $this->result($result, 2, $this->model->getError());
        }
        return $this->result($params, 0, $result);

    }

    public function del()
    {
        $id = $this->request->param('id');
        $result = $this->model->where('id', '>', $id)->delete();

    }

    public function index()
    {
        list($where, $sort, $order, $offset, $limit) = $this->buildparams();
        // total
        return $this->result($this->model->select(), 0);

    }
}
